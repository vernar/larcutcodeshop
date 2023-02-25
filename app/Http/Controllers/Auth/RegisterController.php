<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterPostRequest;
use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
    public function page(): Factory|View|Application
    {
        return view('auth.register');
    }

    public function handle(
        RegisterPostRequest $request,
        RegisterNewUserContract $action
    ): RedirectResponse {
        $action(NewUserDTO::fromRequest($request));

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }
}
