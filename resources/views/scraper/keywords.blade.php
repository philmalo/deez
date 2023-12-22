@extends('layout.app')
@section('content')
<div>
    <h1>{{$mot}}</h1>
    @foreach($keywords as $i => $keyword)
        <h3>Numéro {{$i + 1}}</h3>
        <p>{{$keyword}}</p>
    @endforeach
</div>
@endsection