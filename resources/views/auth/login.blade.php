@extends('layout.app')
@section('title', __('messages.log_in'))
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css')}}">
@endpush
<!-- variable pour cacher la navigation du layout -->
@php $cacherLayout = true @endphp
@section('content')
<main class="login">
    <!-- Session Status -->
    <x-auth-session-status class="" :status="session('status')" />
    <!-- le logo -->
    <div>
        <a href="/">
            <x-application-logo/>
        </a>
    </div>
    <form class="loginForm" method="POST" action="{{ route('login') }}">
        @csrf
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('messages.email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')"/>
        </div>
        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('messages.password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>
        <!-- Remember Me -->
        <div>
            <label for="remember_me">
                <input class="input-remember" id="remember_me" type="checkbox" name="remember">
                <span class="span-remember">{{ __('messages.remember_me') }}</span>
            </label>
        </div>
        <div class="loginActions">
            <div>
                @if (Route::has('password.request'))
                <a class="underline" href="{{ route('password.request') }}">{{ __('messages.forgot_password') }}</a>
                @endif
                <a class="underline" href="{{ route('register')}}">{{ __('messages.create_account') }}</a>
            </div>
            <x-primary-button>{{ __('messages.log_in') }}</x-primary-button>
        </div>
    </form>
</main>
@endsection