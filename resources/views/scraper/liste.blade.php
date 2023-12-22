@extends('layout.app')
@section('content')
@if(count($erreurs) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach($erreurs as $erreur)
                <li>{{ $erreur }}</li>
            @endforeach
        </ul>
    </div>
@endif
<p>Nombre de codes SAQ traités : {{ $codesTraites }}</p>
<p>Nombre de bouteilles dans la BD : {{ $totalBouteilles }}</p>
@if(empty($erreurs))
  <p>Aucune erreur détectée!</p>
@endif
<main class="demo-liste">
  <h1 class="titre-principal"> INFOS SCRAPER</h1>
  @if(count($bouteilles) > 0)
  @php $bouteilles = $bouteilles->slice(-30) @endphp
  @foreach ($bouteilles as $bouteille)
  <div class="ensemble">
    <div class="info-row">
        <div class="pastilles-bouteilles-images">
          <div class="pastille-image">
            @if(empty($bouteille['image_pastille']))
            {{-- <p>cette bouteille n'a pas de pastille de goût</p> --}}
            @else
            {{-- <p>{{$bouteille['image_pastille_alt']}}</p> --}}
              <img src="{{asset('pastilles/' . $bouteille['image_pastille'])}}" alt="{{$bouteille['image_pastille_alt']}}" style="max-height: 65px">
            @endif
          </div>
          <div class="bouteille-image">
            <img src="{{ asset('images/' . $bouteille['image_bouteille']) }}" alt="{{ $bouteille['image_bouteille_alt'] }}" style="max-height: 350px;">
          </div>
        </div>
        <div class="title-desc">
          <div>
            <h1 style="width:50ch">{{$bouteille['nom']}}</h1>
          </div>
          <div class="localisation-box">
              <p>{{ $bouteille['couleur_' . $lang] }} | {{ $bouteille['pays_' . $lang] }}@if(!empty($bouteille['region_' . $lang])), {{ $bouteille['region_' . $lang] }}@endif</p>
          </div>
          <div>
            {{-- <h3>Informations pertinentes</h3> --}}
                <p class="infos" style="width:95ch;">{{$bouteille['description_' . $lang] ?: 'cette bouteille n\'a pas de texte descriptif'}}</p>
            </div>
          <div class="prix-box">
            <p>{{$bouteille['prix']}}</p>
          </div>
        </div>
      </div>
      <div class="infos" style="width: 1346px;">
        <div class="infos-title">
          <h3>infos Détaillées</h3>
        </div>
        <div class="infos-detailles">
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">@if($lang === "fr") Pays : @else Country : @endif</p>
            <p class="infos-detailles-text">{{ $bouteille['pays_' . $lang] ?: 'Non disponible' }}</p>
          </div>
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">@if($lang === "fr") Région : @else Region : @endif</p>
            <p class="infos-detailles-text">{{ $bouteille['region_' . $lang] ?: 'Non disponible' }}</p>
          </div>
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">@if($lang === "fr") Désignation réglementée : @else Regulated designation : @endif</p>
            <p class="infos-detailles-text">{{ $bouteille['designation_reglementee_' . $lang] ?: 'Non disponible' }}</p>
          </div>
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">Classification : </p>
            <p class="infos-detailles-text">{{ $bouteille['classification_' . $lang] ?: 'Non disponible' }}</p>
          </div>
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">@if($lang === "fr") Cépage : @else Grape variety : @endif</p>
            <p class="infos-detailles-text">{{ $bouteille['cepage'] ?: 'Non disponible' }}</p>
          </div>
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">@if($lang === "fr") Taux d'alcool : @else Degree of alcohol : @endif</p>
            <p class="infos-detailles-text">{{ $bouteille['degree_alcool'] ?: 'Non disponible' }}</p>
          </div>
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">@if($lang === "fr") Taux de sucre : @else Sugar content : @endif</p>
            <p class="infos-detailles-text">{{ $bouteille['sucrosite_' . $lang] ?: 'Non disponible' }}</p>
          </div>
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">@if($lang === "fr") Couleur : @else Color : @endif</p>
            <p class="infos-detailles-text">{{ $bouteille['couleur_' . $lang] ?: 'Non disponible' }}</p>
          </div>
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">@if($lang === "fr") Format : @else Size : @endif</p>
            <p class="infos-detailles-text">{{ $bouteille['format'] ?: 'Non disponible' }}</p>
          </div>
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">@if($lang === "fr") Producteur : @else Producer : @endif</p>
            <p class="infos-detailles-text">{{ $bouteille['producteur'] ?: 'Non disponible' }}</p>
          </div>
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">@if($lang === "fr") Agent Promotionnel : @else Promoting Agent : @endif</p>
            <p class="infos-detailles-text">{{ $bouteille['agent_promotionnel'] ?: 'Non disponible' }}</p>
          </div>
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">@if($lang === "fr") Code SAQ : @else SAQ Code : @endif</p>
            <p class="infos-detailles-text">{{ $bouteille['code_SAQ'] ?: 'Non disponible' }}</p>
          </div>
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">@if($lang === "fr") Code CUP : @else UPC Code : @endif</p>
            <p class="infos-detailles-text">{{ $bouteille['code_CUP'] ?: 'Non disponible' }}</p>
          </div>
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">@if($lang === "fr") Arômes : @else Aromas : @endif</p>
            <p class="infos-detailles-text">{{ $bouteille['aromes_' . $lang] ?: 'Non disponible' }}</p>
          </div>
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">@if($lang === "fr") Acidité : @else Acidity : @endif</p>
            <p class="infos-detailles-text">{{ $bouteille['acidite_' . $lang] ?: 'Non disponible' }}</p>
          </div>
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">@if($lang === "fr") Sucrosité : @else Sweetness : @endif</p>
            <p class="infos-detailles-text">{{ $bouteille['sucrosite_' . $lang] ?: 'Non disponible' }}</p>
          </div>
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">@if($lang === "fr") Corps : @else Body : @endif</p>
            <p class="infos-detailles-text">{{ $bouteille['corps_' . $lang] ?: 'Non disponible' }}</p>
          </div>
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">@if($lang === "fr") Bouche : @else Pallet : @endif</p>
            <p class="infos-detailles-text">{{ $bouteille['bouche_' . $lang] ?: 'Non disponible' }}</p>
          </div>
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">@if($lang === "fr") Bois : @else Wood : @endif</p>
            <p class="infos-detailles-text">{{ $bouteille['bois_' . $lang] ?: 'Non disponible' }}</p>
          </div>
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">@if($lang === "fr") Température de service : @else Service temperature : @endif</p>
            <p class="infos-detailles-text">{{ $bouteille['temperature_' . $lang] ?: 'Non disponible' }}</p>
          </div>
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">@if($lang === "fr") Millésime : @else Vintage : @endif</p>
            <p class="infos-detailles-text">{{ $bouteille['millesime'] ?: 'Non disponible' }}</p>
          </div>
          <div class="infos-detailles-carte">
            <p class="infos-detailles-title">@if($lang === "fr") Potentiel de garde : @else Aging potential : @endif</p>
            <p class="infos-detailles-text">{{ $bouteille['potentiel_de_garde_' . $lang] ?: 'Non disponible' }}</p>
          </div>
        </div>
      </div>
    </div>
    <div class="custom-hr"></div>
  @endforeach
@else
  <p>Aucune bouteille trouvée.</p>
@endif
@endsection
</main>