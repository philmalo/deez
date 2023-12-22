<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @push('scripts')
        @vite(['resources/js/app.js'])
        <script src="{{ asset('js/hamburger.js')}}"></script>
    @endpush
    @stack('styles')
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha384-Ms5qXNxHPT+B0DnH6X60r0Z9Cxsijp5ecUTM/Lm5prMwQ7PJhqW8wDjhWcSLgG9m" crossorigin="anonymous"> --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href=" {{ asset('css/layout-lr.css') }}" rel="stylesheet">
    <link href=" {{ asset('css/hamburger.css') }}" rel="stylesheet">
    <link href=" {{ asset('css/root.css') }}" rel="stylesheet">
    <link href=" {{ asset('css/messages.css') }}" rel="stylesheet">
    <link rel="author" href="humans.txt" />
    <title>@yield('title')</title>
</head>
<body>
    <header>
        <div class="blue-top"></div>
        <nav>
            <div class="logo">
                {{-- <img src="{{ asset('logos/deez_wines_logo_small.svg') }}" alt="Logo"> --}}
            </div>
            @if(!isset($cacherLayout))
                <div class="search-more">
                    {{-- <a href="#">
                        <img src="{{ asset('icons/more_icon.svg') }}" alt="Plus">
                    </a> --}}
                </div>
                <a class="hamburger">
                    <img src="{{ asset('icons/more_icon.svg') }}" alt="Plus">
                </a>
            @endif
        </nav>
        <div class="grey-top"></div>
    </header>
    <section class="mobile-nav">
        <div class="deconnexion-div">
            <div class="deconnexion-button">
                <a href="{{ route('logout') }}">@lang('messages.log_out')</a>
            </div>
        </div>
        <div class="deconnexion-menu">
            <a href="{{ route('profile.edit') }}">@lang('messages.profile')</a>
            @if(auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('admin.index') }}">@lang('admin.admin')</a>
            @endif
            <a href="{{ route('bouteilles.create', Auth::id()) }}">@lang('messages.add_custom_bottle')</a>
            <a href="{{ route('liste_achat.index') }}">@lang('messages.your_list')</a>
            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                @if($localeCode != LaravelLocalization::getCurrentLocale())
                    <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                        {{ $properties['native'] }}
                    </a>
                @endif
            @endforeach
        </div>

        <div class="deconnexion-app-info">
            <div>
                <p>@lang('messages.rights')</p>
            </div>
            <div>
                <p>App Version 0.1</p>
                <p class="humans"><a href="{{ route('humans.txt') }}" target="_blank">humans.txt</a></p>
            </div>
        </div>
    </section>
    <div class="overlay-grey"></div>

        @yield('content')

    <footer class="footer">
    @if(!isset($cacherLayout))
        <div class="footer-icon-tray">
            <a href="{{ route('profile.edit') }}">
                <img class="footer-icon-img" src="{{ asset('icons/profil_icon_white.svg') }}" alt="Profil">
                <p>@lang('messages.profile')</p>
            </a>
            <a href="{{ route('bouteilles.index') }}">
                <img class="footer-icon-img" src="{{ asset('icons/catalogue_icon_white.svg') }}" alt="Catalogue">
                <p>@lang('messages.search')</p>
            </a>
            <a href="{{ route('celliers.index') }}">
                <img class="footer-icon-img" src="{{ asset('icons/cellier_icon_white.svg') }}" alt="Celliers">
                <p>@lang('messages.cellars')</p>
            </a>
            <a href="{{ route('liste_achat.index') }}">
                <img class="footer-icon-img" src="{{ asset('icons/add_icon_white.svg') }}" alt="Liste_achats">
                <p>@lang('messages.list')</p>
            </a>
        </div>
    @endif
    </footer>
    @stack('scripts')
</body>
</html>