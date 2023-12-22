@push('styles') 
<link href=" {{ asset('css/modal.css') }}" rel="stylesheet">
<link href=" {{ asset('css/detail-lr.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
@endpush
@push('scripts')
<script src="{{ asset('js/modal.js')}}"></script>
{{-- <script src="{{ asset('js/form-commentaire.js')}}"></script> --}}
<script src="{{ asset('js/note-etoile.js')}}"></script>
<script src="{{ asset('js/form-modification.js')}}" defer></script>
<script src="{{ asset('js/form-zoom.js')}}" defer></script>
@endpush
@extends('layout.app')
@section('title', __('details.detail_title'))
@section('content')
    
    <main>
        <picture class="bouteille-container">
            @if($bouteille->est_personnalisee)
                <img class="bouteille-img" src="{{ url('glide/imagesPersonnalisees/'. $bouteille->image_bouteille . '?p=detail') }}" alt="{{ $bouteille->image_bouteille_alt }}">
            @else
                <img class="bouteille-img" src="{{ url('glide/images/'. $bouteille->image_bouteille . '?p=detail') }}" alt="{{ $bouteille->image_bouteille_alt }}">
            @endif
        </picture>

        <div class="description-container">
            <div class="carte-titre">
                <h2>{{ $bouteille->nom }}</h2>
                    @php
                        $infoVignette = ($bouteille->{"couleur_" . LaravelLocalization::getCurrentLocale()} ? $bouteille->{"couleur_" . LaravelLocalization::getCurrentLocale()} . " | " : "") . ($bouteille->format ? $bouteille->format . " | " : "") . 
                        ($bouteille->{"pays_" . LaravelLocalization::getCurrentLocale()});
                    @endphp
                <p>{{ $infoVignette }}</p>
            </div>
            <a class="bouton-ajouter" onclick='openModal("{{ $bouteille->nom }}", "{{ $bouteille->id }}", "{{  $infoVignette }}")'>
                @lang('details.add')<img src="{{ asset('icons/cellier_icon_white.svg') }}" alt="Plus">
            </a>

            <div class="informations">
                <h3><img src="{{ asset('icons/dollar_icon.svg') }}" alt=""> {{ $bouteille->prix }}</h3>
                <div class="info-double">
                    <div>
                        <h3>@lang('details.region')</h3>
                        <p>{{ $bouteille->{"region_" . LaravelLocalization::getCurrentLocale()} }}</p>
                    </div>
                    <div>
                        <h3>@lang('details.size')</h3>
                        <p>{{ $bouteille->format }}</p>
                    </div>
                </div>
                <div class="info-double">
                    <div>
                        <h3>@lang('details.degreeofalcohol')</h3>
                        <p>{{ $bouteille->degree_alcool }}</p>
                    </div>
                    <div>
                        <h3>@lang('details.sugarcontent')</h3>
                        <p>{{ $bouteille->taux_de_sucre }}</p>
                    </div>
                </div>

                @if(!empty($bouteille->temperature_fr))
                    <div class="info-simple">
                        <h3>@lang('details.servingtemperature')</h3>
                        <p>{{ $bouteille->temperature_fr }}</p>
                    </div>
                @endif

                @if(!empty($bouteille->cepage))
                    <div class="info-simple">
                        <h3>@lang('details.cepage')</h3>
                        <p>{{ $bouteille->cepage }}</p>
                    </div>
                @endif

                @if(!empty($bouteille->aromes_fr))
                    <div class="info-simple">
                        <h3>@lang('details.aromas')</h3>
                        <p>{{ $bouteille->aromes_fr }}</p>
                    </div>
                @endif
                
                @if(!empty($bouteille->designation_reglementee_fr))
                    <div class="info-simple">
                        <h3>@lang('details.regulateddesignation')</h3>
                        <p>{{ $bouteille->designation_reglementee_fr }}</p>
                    </div>
                @endif

                @if(!empty($bouteille->producteur))
                    <div class="info-simple">
                        <h3>@lang('details.producer')</h3>
                        <p>{{ $bouteille->producteur }}</p>
                    </div>
                @endif

                @if(!empty($bouteille->agent_promotionnel))
                    <div class="info-simple">
                        <h3>@lang('details.promotionagent')</h3>
                        <p>{{ $bouteille->agent_promotionnel }}</p>
                    </div>
                @endif

                <div class="division"></div>

                @if(!empty($bouteille->description_fr))
                    <div class="info-detaillee">
                        <h3>@lang('details.detailedinformation')</h3>
                        <p>{{ $bouteille->description_fr }}</p>
                    </div>
                    
                    <div class="division"></div>
                @endif
                
                @if(!empty($bouteille->acidite_fr)) {{-- pourrait etre n'importe quel valeur de gout --}}
                    <section class="info-gouts-container">

                        @php

                            $acidity = __('details.acidity');
                            $sweetness = __('details.sweetness');
                            $body = __('details.body');
                            $mouthfeel = __('details.mouthfeel');
                            $wood = __('details.wood');


                            // $nom = [$acidity, $sweetness, $body, $mouthfeel, $wood];
                            // $un = [__('details.discreet'), __('details.semisweet'), __('details.light'), __('details.discreet'), __('details.discreet')];
                            // $deux = [__('details.present'),__('details.sweet'),__('details.mediumbodied'),__('details.balanced'), __('details.balanced')];
                            // $trois = [__('details.dominant'),__('details.extrasweet'),__('details.fullbodied'),__('details.dominant'),__('details.dominant')];

                            $valeurs = [$bouteille->{"acidite_" . LaravelLocalization::getCurrentLocale()}, $bouteille->{"sucrosite_" . LaravelLocalization::getCurrentLocale()}, $bouteille->{"corps_" . LaravelLocalization::getCurrentLocale()}, $bouteille->{"bouche_" . LaravelLocalization::getCurrentLocale()}, $bouteille->{"bois_" . LaravelLocalization::getCurrentLocale()}];
                            $nom = [$acidity, $sweetness, $body, $mouthfeel, $wood];
                            $un = [__('details.discreet_acidity'), __('details.semisweet'), __('details.light'), __('details.delicate'), __('details.discreet_wood')];
                            $deux = [__('details.present_acidity'),__('details.sweet'),__('details.mediumbodied'),__('details.generous'), __('details.balanced_wood')];
                            $trois = [__('details.dominant_acidity'),__('details.extrasweet'),__('details.fullbodied'),__('details.opulent'),__('details.dominant_wood')];

                            $position = 0;
                        @endphp

                        @for($i = 0; $i < count($valeurs); $i++)
                            <div class="info-gouts">
                                @if($valeurs[$position] != null)
                                    <div class="texte-gouts">
                                        <h3>{{ $nom[$position] }}</h3><p>{{ $valeurs[$position] }}</p>
                                    </div>
                                    <div class="ligne-container">
                                        <div class="ligne-gauche" @if(in_array($valeurs[$position], $un) || in_array($valeurs[$position], $deux) || in_array($valeurs[$position], $trois)) style="background-color: var(--ligne-gout);" @endif></div>
                                        <div class="ligne-centre" @if(in_array($valeurs[$position], $deux) || in_array($valeurs[$position], $trois)) style="background-color: var(--ligne-gout);" @endif></div>
                                        <div class="ligne-droite" @if(in_array($valeurs[$position], $trois)) style="background-color: var(--ligne-gout);" @endif></div>
                                    </div>
                                @endif
                            </div>
                            @php $position++; @endphp
                        @endfor

                        @if($bouteille->acidite_fr != null)
                            <div class="division"></div>
                        @endif
                    </section>
                @endif
            </div>

            <div class="commentaire-note">

                @if(!empty($commentaireBouteille->commentaire))
                    <div class="commentaire-existant form-visible">
                            <div class="commentaire">
                                <h3>@lang('details.comments') : </h3>
                                <p>{{ $commentaireBouteille->commentaire }}</p>
                            </div>
                        @endif
                        
                        @if(!empty($commentaireBouteille->note))
                            <div class="note">
                                <h3>@lang('details.rating')</h3>
                                <p>{{ $commentaireBouteille->note }}/5</p>
                            </div>
                        @endif
                        @if(!empty($commentaireBouteille->note) || !empty($commentaireBouteille->commentaire))
                            <div class="bouton-modifier">
                                <a id="btn-modifier-commentaire" type="button">
                                    Modifer <span class="material-symbols-outlined exclure">edit_note</span>
                                </a>
                            </div>
                        </div>
                    @endif

                    @if(empty($commentaireBouteille->commentaire) && empty($commentaireBouteille->note))
                            <form id="form-commentaire" class="{{ empty($commentaireBouteille) ? 'form-visible' : 'form-invisible' }}" action="{{ route('commentaire_bouteille.store') }}" method="POST">
                        @csrf
                    @else
                        <form id="form-commentaire" class="form-invisible" action="{{ route('commentaire_bouteille.update', $commentaireBouteille->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                    @endif

                        <label for="commentaire">@lang('details.comments') : </label>
                        <textarea name="commentaire" id="commentaire" cols="30" rows="6" data-commentaire="{{ $commentaireBouteille->commentaire ?? '' }}"></textarea>
                        <input type="hidden" name="bouteille_id" value="{{ $bouteille->id }}">
                        <label for="note">@lang('details.rating') : </label>

                            <div class="note-etoile">
                            <span class="etoile material-symbols-outlined" data-note="1">wine_bar</span>
                            <span class="etoile material-symbols-outlined" data-note="2">wine_bar</span>
                            <span class="etoile material-symbols-outlined" data-note="3">wine_bar</span>
                            <span class="etoile material-symbols-outlined" data-note="4">wine_bar</span>
                            <span class="etoile material-symbols-outlined" data-note="5">wine_bar</span>
                            </div>
                            
                            <input type="hidden" name="note" id="note" value="0">

                            <div class="bouton-submit">
                                <button type="submit" class="invisible-385px">@lang('details.add')</button><img src="{{ asset('icons/plus_icon.svg') }}" alt="Plus">
                            </div>
                        </form>
            </div>
        </div>
</main>
    
@include('components.modals.modale-ajout-bouteille')

@endsection