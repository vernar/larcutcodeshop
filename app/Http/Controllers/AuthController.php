<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginPostFormRequest;
use App\Http\Requests\PasswordForgotPostRequest;
use App\Http\Requests\PasswordResetPostRequest;
use App\Http\Requests\registerPostFormRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function index()
    {
        return $this->signUp();
    }

    public function login()
    {
        return view('auth.login');
    }

    public function loginPost(LoginPostFormRequest $request): RedirectResponse
    {
        // TODO 3.1 Rate Limit
        if (!auth()->attempt($request->validated())) {
            return back()->withErrors([
                'email' => 'Пользователь не найден',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function register()
    {
        return view('auth.register');
    }

    public function registerPost(registerPostFormRequest $request): RedirectResponse
    {
        $user = User::query()->create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        event(new Registered($user));
        auth()->login($user);

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->intended(route('home'));
    }

    public function forgotPassword(): Factory|View|Application
    {
        return view('auth.forgot-password');
    }

    public function forgotPasswordPost(PasswordForgotPostRequest $request): RedirectResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // TODO 3.1 flash
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['message' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(string $token): Factory|View|Application
    {
        return view('auth.reset-password', [
            'token' => $token,
        ]);
    }

    public function resetPasswordPost(PasswordResetPostRequest $request): RedirectResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])->setRememberToken(str()->random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('message', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
