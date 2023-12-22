@extends('layout.app')
@section('title', __('admin.user_manage'))
@push('styles')
    <link href=" {{ asset('css/modal.css') }}" rel="stylesheet">
    <link href=" {{ asset('css/auth.css') }}" rel="stylesheet">
@endpush
@section('content')
<main>
    @include('admin.partials.nav')
    <h1>@lang('admin.user_manage')</h1>
    @if (session('success'))
        <div class="alert-success" role="alert">{{ session('success') }}</div>
    @endif
    <table>
        <thead>
            <tr>
                <th>@lang('admin.user_name')</th>
                <th>@lang('admin.user_id')</th>
                <th>@lang('admin.user_cellars_qty')</th>
                <th>@lang('admin.user_actions')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usagersAvecCelliers as $usager)
                <tr>
                    <td>{{ $usager->name }}</td>
                    <td>{{ $usager->id }}</td>
                    <td>{{ $usager->celliers_count }}</td>
                    <td>
                    <button class="boutonUpdate" data-id="{{ $usager->id }}" data-username="{{ $usager->name }}" data-email="{{ $usager->email }}">@lang('admin.update_user')</button>
                    @if($usager->role != 'admin')
                    <form class="formulaireDel" action="{{ route('admin.destroy', $usager->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="boutonSupp" type="submit" data-text="@lang('supprimer.del_user')" data-title="@lang('supprimer.del_user_title')">@lang('messages.delete_account')</button>
                    </form>
                    @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</main>
@include('components.modals.modale-modifier-user')
@include('components.modals.modale-confirmer-suppression')
@endsection
@push('scripts')
    <script src="{{ asset('js/confirmerSupp.js')}}"></script>
    <script src="{{ asset('js/adminUpdateUser.js')}}"></script>
@endpush