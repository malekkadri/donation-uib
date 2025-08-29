@extends('layouts.app')
@section('css')
<style>
    .leaderboard-badge {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: bold;
    }
    .badge-gold {
        background-color: #FFD700;
        color: #000;
    }
    .badge-silver {
        background-color: #C0C0C0;
        color: #000;
    }
    .badge-bronze {
        background-color: #CD7F32;
        color: #fff;
    }
    .donor-row {
        transition: all 0.3s ease;
    }
    .donor-row:hover {
        background-color: rgba(237, 28, 36, 0.05);
    }
</style>
@endsection
@section('content')
    <div class="container py-5">
        <h1 class="section-title text-center mb-5">Tableau des donateurs</h1>
        
        <!-- Stats Cards -->
        <div class="row mb-5">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="card border-0 shadow-sm h-100 shadow-hover">
                    <div class="card-body p-4 text-center">
                        <div class="display-4 text-uib-red mb-2">TND {{ number_format($donors->sum('amount'), 3, '.', ',') }}</div>
                        <h5 class="text-muted">Total des dons</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="card border-0 shadow-sm h-100 shadow-hover">
                    <div class="card-body p-4 text-center">
                        <div class="display-4 text-uib-red mb-2">{{ $donors->count() }}</div>
                        <h5 class="text-muted">Nombre de donateurs</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 shadow-hover">
                    <div class="card-body p-4 text-center">
                        <div class="display-4 text-uib-red mb-2">TND {{ $donors->count() > 0 ? number_format($donors->sum('amount') / $donors->count(), 3, '.', ',') : '0.000' }}</div>
                        <h5 class="text-muted">Don moyen</h5>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Leaderboard Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-uib-red text-white py-3">
                <div class="d-flex align-items-center">
                    <i class="fa fa-trophy me-2"></i>
                    <h4 class="mb-0">Meilleurs donateurs</h4>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Nom</th>
                                <th class="text-center">Localisation</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($donors->count())
                                @foreach ($donors as $key => $donor)
                                    <tr class="donor-row">
                                        <td class="text-center align-middle">
                                            @if($key < 3)
                                                <div class="leaderboard-badge {{ $key == 0 ? 'badge-gold' : ($key == 1 ? 'badge-silver' : 'badge-bronze') }} mx-auto">
                                                    {{ $key + 1 }}
                                                </div>
                                            @else
                                                {{ $key + 1 }}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                    <i class="fa fa-user text-uib-red"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $donor->name }}</h6>
                                                    <small class="text-muted">{{ $donor->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <i class="fa fa-map-marker-alt text-uib-red me-1"></i>
                                            {{ $donor->city_name }}, {{ $donor->country_name }}
                                        </td>
                                        <td class="text-center align-middle">
                                            <i class="far fa-calendar-alt text-uib-red me-1"></i>
                                            {{ $donor->created_at?->format('d M Y') }}
                                        </td>
                                        <td class="text-end align-middle fw-bold">
                                            <span class="badge bg-light text-uib-red p-2">
                                                TND {{ number_format($donor->amount, 3, '.', ',') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="fa fa-ghost fa-3x text-muted mb-3"></i>
                                        <h5 class="mb-3">Aucun donateur à afficher pour le moment</h5>
                                        <a href="{{ route('home.donate') }}" class="btn btn-primary">
                                            <i class="fa fa-hand-holding-heart me-2"></i> Soyez le premier à faire un don
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $donors->onEachSide(1)->links() }}
        </div>
        
        <!-- Call to Action -->
        <div class="text-center mt-5">
            <h4 class="mb-3">Vous souhaitez rejoindre nos donateurs?</h4>
            <p class="text-muted mb-4">Votre contribution peut faire une différence significative dans la vie de quelqu'un.</p>
            <a href="{{ route('home.donate') }}" class="btn btn-primary">
                <i class="fa fa-hand-holding-heart me-2"></i>Faire un don
            </a>
        </div>
    </div>
@endsection
@section('javascript')
@endsection