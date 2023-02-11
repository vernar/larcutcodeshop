@extends('layouts.app')

@section('content')
    @guest
        <ul>
            <li><a href="{{ route('login') }}">Войти</a></li>
            <li><a href="{{ route('register') }}">Зарегистрироваться</a></li>
        </ul>
    @endguest
    @auth
        <form method="post" action="{{ route('logout') }}">
            @csrf
            @method('DELETE')
            <button type="submit">Выйти</button>
        </form>
    @endauth

@endsection