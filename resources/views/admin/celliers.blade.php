@extends('layout.app')
@section('title', __('admin.cellars_mgmt'))
@section('content')
    <main>
        @include('admin.partials.nav')

        <h2>@lang('admin.stats_cellars')</h2>

        <p>@lang('admin.stats_cellars') : {{ $totalMontantCelliers }} $</p>

        <h2>@lang('admin.stats_users_cellars')</h2>
        @foreach ($usersWithCelliers as $user)
            <h3>{{ $user->name }}</h3>
            <p>@lang('admin.cellars_total_bottles') : {{ $user->totalBouteilles }}</p>
            <p>@lang('admin.total_value_user_cellars') : {{ $user->totalMontant }} $</p>
            
            <table>
                <thead>
                    <tr>
                        <th>@lang('admin.tab_cellar')</th>
                        <th>@lang('admin.tab_amount')</th>
                        <th>@lang('admin.tab_bottle_qty')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->celliers as $cellier)
                        <tr>
                            <td>{{ $cellier->nom }}</td>
                            <td>{{ $montantsParUsagerCellier[$user->id][$cellier->id]['montant'] }} $</td>
                            <td>{{ $montantsParUsagerCellier[$user->id][$cellier->id]['nombre_bouteilles'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </main>
@endsection