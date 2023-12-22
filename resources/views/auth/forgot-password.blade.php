@extends('layout.app')
@section('title', __('messages.register'))
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css')}}">
@endpush
<!-- variable pour cacher la navigation du layout -->
@php $cacherLayout = true @endphp
@section('content')
<main class="login">
    <div>
        <a href="/">
            <x-application-logo/>
        </a>
    </div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form class="loginForm" method="POST" action="{{ route('password.email') }}">
        @csrf
        <div>
            {{ __('messages.forgot_password_text') }}
        </div>
        <hr>
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('messages.email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="loginActions">
            <a href="{{ route('login') }}">{{ __('messages.cancel') }}</a>
            <x-primary-button>{{ __('messages.send_email') }}</x-primary-button>
        </div>
    </form>
</main>
@endsection
