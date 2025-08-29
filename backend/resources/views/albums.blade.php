@extends('layouts.app')

@section('css')
<style>
    .album-card {
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .album-card:hover {
        transform: translateY(-5px);
    }
    .album-card .carousel-item {
        transition: transform 0.6s ease-in-out;
    }
    .album-image {
        height: 200px;
        background-size: cover;
        background-position: center;
        transition: all 0.5s ease;
    }
    .album-card:hover .album-image {
        transform: scale(1.05);
    }
    .share-btn {
        transition: all 0.3s ease;
    }
    .share-btn:hover {
        transform: translateY(-3px);
    }
</style>
@endsection

@section('content')
    <main role="main">
        <!-- Hero Section -->
        <section class="bg-light py-5 mb-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <h1 class="section-title">Notre Galerie</h1>
                        <p class="lead text-muted">
                            Explorez notre collection de moments mémorables et d'initiatives impactantes. Ces images témoignent de notre engagement à faire une différence dans les communautés que nous servons.
                        </p>
                        <a href="{{ route('home.donate') }}" class="btn btn-primary mt-3">
                            <i class="fa fa-hand-holding-heart me-2"></i>Soutenez notre cause
                        </a>
                    </div>
                    <div class="col-lg-6">
                        <img src="{{ asset('images/gallery-hero.jpg') }}" alt="Galerie Héro" class="img-fluid rounded shadow" onerror="this.src='https://via.placeholder.com/600x400?text=UIB+Galerie'">
                    </div>
                </div>
            </div>
        </section>

        <!-- Albums Section -->
        <div class="container mb-5">
            <div class="row">
                @foreach($albums as $album)
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm album-card h-100">
                        <div id="carousel{{$album->id}}" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                            <div class="carousel-inner">
                                @foreach($album->media as $k => $media)
                                <div class="carousel-item {{ $k == 0 ? "active" : "" }}">
                                    <div class="album-image" style="background: url('{{ asset('images/albums/'.$media->name)}}') center center no-repeat;"></div>
                                </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel{{$album->id}}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Précédent</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel{{$album->id}}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Suivant</span>
                            </button>
                            <div class="carousel-indicators position-absolute mb-0" style="bottom: -5px;">
                                @foreach($album->media as $k => $media)
                                <button type="button" data-bs-target="#carousel{{$album->id}}" data-bs-slide-to="{{$k}}" class="{{ $k == 0 ? 'active' : '' }}" aria-current="{{ $k == 0 ? 'true' : 'false' }}" aria-label="Diapositive {{$k+1}}"></button>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-uib-black">{{$album->name}}</h5>
                            <p class="card-text text-muted small">{{\Str::words(strip_tags($album->description), 15, '...')}}</p>
                        </div>
                        <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                            <a href="https://wa.me/?text=Découvrez%20cette%20galerie%20incroyable%0A{{ route('home.album', $album->id) }}" class="btn btn-outline-success btn-sm share-btn" target="_blank" data-bs-toggle="tooltip" title="Partager sur WhatsApp">
                                <i class="fab fa-whatsapp"></i> Partager
                            </a>
                            <a href="{{ route('home.album', $album->id) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-eye me-1"></i> Voir détails
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $albums->onEachSide(1)->links() }}
            </div>
        </div>

        <!-- Call to Action -->
        <div class="bg-light py-5 text-center">
            <div class="container">
                <h3 class="mb-4">Vous souhaitez contribuer à notre galerie ?</h3>
                <p class="text-muted mb-4">Si vous avez des photos de nos événements ou initiatives, nous serions ravis de les présenter dans notre galerie.</p>
                <a href="{{ route('home.contact') }}" class="btn btn-primary">
                    <i class="fa fa-paper-plane me-2"></i>Contactez-nous
                </a>
            </div>
        </div>
    </main>
@endsection

@section('javascript')
    <script>
        $(function() {
            // Initialize tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        });
    </script>
@endsection