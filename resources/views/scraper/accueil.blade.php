@extends('layout.app')
@section('content')
<div>
    <h1>{{$mot}}</h1>
    @if(false)
        @foreach($tests as $i => $test)
            <h3>Numéro {{$i + 1}}</h3>
            <p>{{$test}}</p>
        @endforeach
    @endif
</div>
@endsection