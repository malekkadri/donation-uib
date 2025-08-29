<div class="container my-5">
    <h2 class="section-title text-center mb-4">Tableau des donateurs</h2>
    
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-uib-red text-white py-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <i class="fa fa-trophy me-2"></i>
                    <span class="fw-bold">Meilleurs donateurs</span>
                </div>
                <a href="{{ route('home.leaderboard') }}" class="btn btn-sm btn-light">
                    <i class="fa fa-list me-1"></i>Voir tout
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Nom</th>
                            <th class="text-center d-none d-md-table-cell">Localisation</th>
                            <th class="text-center d-none d-md-table-cell">Date</th>
                            <th class="text-center">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($donors->count())
                            @foreach ($donors->take(5) as $key => $donor)
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
                                                <small class="text-muted d-none d-md-inline">{{ $donor->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle d-none d-md-table-cell">
                                        <i class="fa fa-map-marker-alt text-uib-red me-1"></i>
                                        {{ $donor->city_name }}, {{ $donor->country_name }}
                                    </td>
                                    <td class="text-center align-middle d-none d-md-table-cell">
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
                                <td colspan="5" class="text-center py-4">
                                    <i class="fa fa-ghost fa-2x text-muted mb-2"></i>
                                    <h6 class="mb-2">Aucun donateur à afficher pour le moment</h6>
                                    <a href="{{ route('home.donate') }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-hand-holding-heart me-1"></i> Soyez le premier à faire un don
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>