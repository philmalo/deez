@extends('layout.app')
@section('title', __('messages.dashboard'))
@push('styles')
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="{{ asset('css/auth.css')}}">
    <link rel="stylesheet" href="{{ asset('css/profil.css')}}">
    <link rel="stylesheet" href="{{ asset('css/tailwind-bug.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
@endpush
@section('content')
    <main class="main-profil">
        @include('profile.partials.update-profile-information-form')
        @include('profile.partials.update-password-form')
        @include('profile.partials.delete-user-form')
    </main>
@endsection