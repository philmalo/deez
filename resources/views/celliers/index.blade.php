@extends('layout.app')
@section('title', __('messages.your_cellars'))
@push('styles')
    <link href="{{ asset('css/celliers.css') }}" rel="stylesheet">
    <link href="{{ asset('css/modal.css') }}" rel="stylesheet">
@endpush
@section('content')


@if (session('success'))
    <div class="alert alert-success extra-margin alert-dismissible fade show" role="alert">
        <p>{{ session('success') }}</p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">X</span>
        </button>
    </div>
@endif
@if(count($celliers) == 0)
    <section class="cellier no-cellier-box">
        <p>@lang('messages.no_cellar')</p>
    </section>
@endif
<main class="celliers">
    @foreach($celliers as $cellier)
        <div class="cellier">
            <a href="{{ route('celliers.show', $cellier->id) }}">
                <span>{{ ucfirst($cellier->nom) }}</span>
                <div class="infosCellier">
                    <span>@lang('messages.your_bottles') : {{ $cellier->quantite_bouteilles }}</span>
                    @if($cellier->quantite_bouteilles > 0)
                    <div class="division-blanc"></div>
                    <div>
                        <span>@lang('messages.red') : {{ $cellier->quantiteBouteillesRouges() ?? 0 }}</span><span>@lang('messages.rose') : {{ $cellier->quantiteBouteillesRoses() ?? 0 }}</span><span>@lang('messages.white') : {{ $cellier->quantiteBouteillesBlanches() ?? 0 }}</span> {{-- <span>@lang('messages.orange') : {{ $cellier->quantiteBouteillesOranges() ?? 0 }}</span> --}}
                    </div>
                    @endif
                </div>
            </a>
        </div>
    @endforeach
    <div>
        <button onclick="nouveauCellier()"><img src="{{ asset('icons/plus_icon.svg') }}" alt="Ajouter">@lang('messages.add')</button>
    </div>
</main>
@include('components.modals.modale-ajout-cellier')
@push('scripts')
<script src="{{ asset('js/messages.js') }}"></script>
<script src="{{ asset('js/modal.js') }}"></script>
@endpush
@endsection