<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\Auth\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as RedirectResponseAlias;

class SocialAuthController extends Controller
{
    public function redirect(string $driver = 'github'): RedirectResponseAlias|RedirectResponse
    {
        try {
            return Socialite::driver($driver)->redirect();
        } catch (\Throwable $e) {
            throw new \DomainException('Драйвер не поддерживается');
        }
    }

    public function callback(string $driver = 'github'): RedirectResponse
    {
        if ($driver !== 'github') {
            throw new \DomainException('Драйвер не поддерживается');
        }
        $githubUser = Socialite::driver($driver)->user();

        $user = User::query()->firstOrCreate([
            $driver.'_id' => $githubUser->id,
        ], [
            'name'     => $githubUser->name ?? 'none',
            'email'    => $githubUser->email,
            'password' => bcrypt(str()->random(20)),
        ]);

        Auth::login($user);

        return redirect()->intended(route('home'));
    }
}