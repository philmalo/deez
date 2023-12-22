@extends('layout.app')
@section('title', __('messages.register'))
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css')}}">
@endpush
<!-- variable pour cacher la navigation du layout -->
@php $cacherLayout = true @endphp
@section('content')
<main class="login">
    <form class="loginForm" method="POST" action="{{ route('register') }}">
        @csrf
        <div>
            <a href="/">
                <x-application-logo/>
            </a>
        </div>
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('messages.name')" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('messages.email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('messages.password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('messages.confirm_password')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="loginActions">
            <a href="{{ route('login') }}">{{ __('messages.already_registered') }}</a>
            <x-primary-button>{{ __('messages.register') }}</x-primary-button>
        </div>
    </form>
</main>
@endsection