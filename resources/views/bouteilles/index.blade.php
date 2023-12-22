@extends('layout.app')
@section('title', __('messages.search'))
@section('content')
@push('styles')
    <link href=" {{ asset('css/carte-vin-lr.css') }}" rel="stylesheet">
    <link href=" {{ asset('css/paginate.css') }}" rel="stylesheet">
    <link href=" {{ asset('css/modal.css') }}" rel="stylesheet">
    <link href=" {{ asset('css/cellier-show.css') }}" rel="stylesheet">
    <link href=" {{ asset('css/recherche.css') }}" rel="stylesheet">
    <link href=" {{ asset('css/filtres.css') }}" rel="stylesheet">
@endpush
@push('scripts')
    <script src="{{ asset('js/filtreDropdown.js')}}"></script>
    <script src="{{ asset('js/search.js')}}"></script>
    <script src="{{ asset('js/plusAnimation.js')}}"></script>
@endpush
<header class="rechercheConteneur">
    <input class="rechercheInput" type="text" name="query" id="searchInput" placeholder="@lang('messages.search_bar_message')">
</header>

{{-- SECTION MENU FILTRE SIDE --}}

<section class="filtre-nav">
    <div class="zone-pillules">
    </div>

    <form class="filtres-side-bar" method="GET">
        @csrf
            <div class="filtre">
                <button class="filtre-button-couleurs">@lang('messages.colors') <img class="plus-couleurs filter-green" src="{{ asset('icons/plus_icon_grey.svg') }}" alt="Ouvrir"></button>
                <div id="couleurs" class="filtre filtre-dropdown-couleurs">
                    <div class="label-simple">
                        <label for="filtre-rouge">@lang('messages.red')</label>
                        <input class="input-checkbox" type="checkbox" name="filtre-rouge" id="filtre-rouge" value="{{ __('messages.red')}}">
                    </div>
                    <div class="label-simple">
                        <label for="filtre-blanc">@lang('messages.white')</label>
                        <input class="input-checkbox" type="checkbox" name="filtre-blanc" id="filtre-blanc" value="{{ __('messages.white')}}">
                    </div>
                    <div class="label-simple">
                        <label for="filtre-rose">@lang('messages.rose')</label>
                        <input class="input-checkbox" type="checkbox" name="filtre-rose" id="filtre-rose" value="{{ __('messages.rose')}}">
                    </div>
                    <div class="label-simple">
                        <label for="filtre-orange">@lang('messages.orange')</label>
                        <input class="input-checkbox" type="checkbox" name="filtre-orange" id="filtre-orange" value="{{ __('messages.orange')}}">
                    </div>
                </div>
            </div>

            <div class="filtre">
                <button class="filtre-button-pays">@lang('messages.country') <img class="plus-pays" src="{{ asset('icons/plus_icon_grey.svg') }}" alt="Ouvrir"></button>
                <div id="pays" class="filtre filtre-dropdown-pays">
                @foreach ($pays as $paysClean => $paysOfficiel) 
                    @if($paysOfficiel == 'germany')
                        @continue
                    @endif
                    <div class="label-simple">
                        <label for="filtre-{{ $paysClean }}">
                            {{ $paysOfficiel }}
                        </label>
                        <input class="input-checkbox" type="checkbox" 
                            name="filtre-{{ $paysClean }}"
                            id="filtre-{{ $paysClean }}"
                            value="{{ $paysOfficiel }}">
                    </div>
                @endforeach
                </div>
            </div>

            <div class="filtre">
                <button class="filtre-button-prix">@lang('messages.price') <img class="plus-prix" src="{{ asset('icons/plus_icon_grey.svg') }}" alt="Ouvrir"></button>
                <div id="prix" class="filtre filtre-dropdown-prix">
                    <div class="label-simple">
                        <label for="filtre-1-20">@lang('messages.less_than_20')</label>
                        <input class="input-checkbox" type="checkbox" name="filtre-1-20" id="filtre-1-20" value="1-20">
                    </div>
                    <div class="label-simple">
                        <label for="filtre-20-30">@lang('messages.20_to_30')</label>
                        <input class="input-checkbox" type="checkbox" name="filtre-20-30" id="filtre-20-30" value="20-30">
                    </div>
                    <div class="label-simple">
                        <label for="filtre-30-40">@lang('messages.30_to_40')</label>
                        <input class="input-checkbox" type="checkbox" name="filtre-30-40" id="filtre-30-40" value="30-40">
                    </div>
                    <div class="label-simple">
                        <label for="filtre-40-50">@lang('messages.40_to_50')</label>
                        <input class="input-checkbox" type="checkbox" name="filtre-40-50" id="filtre-40-50" value="40-50">
                    </div>
                    <div class="label-simple">
                        <label for="filtre-50-60">@lang('messages.50_to_60')</label>
                        <input class="input-checkbox" type="checkbox" name="filtre-50-60" id="filtre-50-60" value="50-60">
                    </div>
                    <div class="label-simple">
                        <label for="filtre-60+">@lang('messages.more_than_60')</label>
                        <input class="input-checkbox" type="checkbox" name="filtre-60" id="filtre-60" value="60">
                    </div>
                </div>
            </div>

            <div class="filtre">
                <button class="filtre-button-cepages">@lang('messages.grape_variety') <img class="plus-cepages" src="{{ asset('icons/plus_icon_grey.svg') }}" alt="Ouvrir"></button>
                <div id="cepages" class="filtre filtre-dropdown-cepages">
                    @foreach($cepages as $c)
                        @php
                            $cUnderscored = str_replace(' ', '_', $c);
                        @endphp
                        <div class="label-simple">
                            <label for="filtre-{{$cUnderscored}}">{{$c}}</label>
                            <input class="input-checkbox" type="checkbox" name="filtre-{{$cUnderscored}}" id="filtre-{{$cUnderscored}}" value="{{$c}}">
                        </div>
                    @endforeach
                </div>
            </div>

        <div class="filtre">
            <button class="filtre-button-pastilles">@lang('pastilles.pastille') <img class="plus-pastilles" src="{{ asset('icons/plus_icon_grey.svg') }}" alt="Ouvrir"></button>
            <div id="pastilles" class="filtre filtre-dropdown-pastilles">
                @foreach($pastilles as $p)
                    @php
                        $parts = explode(' : ', $p->image_pastille_alt);
                        $pastilleValue = isset($parts[1]) ? $parts[1] : ''; // Get the second part if available
                        $translatedPastille = "";
                        $pastilleTableau = [
                                        'Fruité et léger' => __('pastilles.fl'),
                                        'Fruité et généreux' => __('pastilles.fmb'),
                                        'Aromatique et charnu' => __('pastilles.ar'),
                                        'Aromatique et souple' => __('pastilles.as'),
                                        'Délicat et léger' => __('pastilles.dl'),
                                        'Fruité et vif' => __('pastilles.fv'),
                                        'Aromatique et rond' => __('pastilles.am'),
                                        'Fruité et doux' => __('pastilles.fs'),
                                        'Fruité et extra-doux' => __('pastilles.fes'),
                                    ];

                        $translatedPastille = $pastilleTableau[$pastilleValue] ?? '';
                        $pUnderscored = str_replace(' ', '_', $translatedPastille);
                    @endphp
                    @if($translatedPastille != '')
                        <div class="label-simple">
                            <label for="filtre-{{$pUnderscored}}">{{$translatedPastille}}</label>
                            <input class="input-checkbox" type="checkbox" name="filtre-{{$pUnderscored}}" id="filtre-{{$pUnderscored}}" value="{{$translatedPastille}}">
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </form>
</section>
<div class="overlay-grey-filtre"></div>

{{-- FIN -- SECTION MENU FILTRE SIDE --}}


<div class="filtres-tris-conteneur">
    <div class="filtres-trigger">
        <span class="material-symbols-outlined">filter_list</span>
        <p>@lang('messages.filters')<span class="span-number"></span></p>
    </div>
    <div class="tris-trigger">
        <span class="material-symbols-outlined">sort</span>
        <p>@lang('messages.sort')</p>
    </div>
</div>

<h3 class="resultats"></h3>

<main class="indexBouteilles">

</main>
@include('components.modals.modale-tri-bouteilles')
<script>
    let selectedLanguage = @json(app()->getLocale());
    let pastilleTableau = @json($pastilleTableau);
</script>
@include('components.modals.modale-ajout-bouteille')
@endsection
@push('scripts')
<script src="{{ asset('js/modal.js')}}"></script>
<script src="{{ asset('js/search.js')}}"></script>
@endpush