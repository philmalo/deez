@extends('layout.app')
@section('content')
<div>
    <h1>{{$mot}}</h1>
    <p>Nombre de codes SAQ ajoutés : {{ $bouteillesAjoutees }}</p>
    <p>Nombre de bouteilles dans la BD : {{ $totalBouteilles }}</p>
    <p>Nombre de bouteilles désactivées : {{ $bouteillesDesactivees }}</p>
</div>
@endsection