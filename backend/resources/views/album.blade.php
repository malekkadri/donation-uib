@extends('layouts.app')

@section('css')
<style>
    .album-image {
        height: 400px;
        background-size: cover;
        background-position: center;
    }
    .share-btn {
        transition: all 0.3s ease;
    }
    .share-btn:hover {
        transform: translateY(-3px);
    }
    .album-meta {
        font-size: 0.9rem;
    }
    .album-description {
        line-height: 1.8;
    }
</style>
@endsection

@section('content')
    <main role="main">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home.index') }}" class="text-decoration-none">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('home.albums') }}" class="text-decoration-none">Galerie</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $album->name }}</li>
                        </ol>
                    </nav>
                    
                    <!-- Album Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="section-title mb-0">{{ $album->name }}</h1>
                        <div class="d-flex align-items-center">
                            <span class="text-muted album-meta me-3">
                                <i class="far fa-calendar-alt me-1"></i> {{ $album->created_at?->format('d M Y') }}
                            </span>
                            <a href="https://wa.me/?text=Découvrez%20cette%20galerie%20incroyable%0A{{ route('home.album', $album->id) }}" class="btn btn-outline-success btn-sm share-btn" target="_blank">
                                <i class="fab fa-whatsapp me-1"></i> Partager
                            </a>
                        </div>
                    </div>

                    <!-- Album Carousel -->
                    <div class="card border-0 shadow-sm mb-5">
                        <div id="albumCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($album->media as $k => $media)
                                <div class="carousel-item {{ $k == 0 ? "active" : "" }}">
                                    <div class="album-image" style="background: url('{{ asset('images/albums/'.$media->name)}}') center center no-repeat;"></div>
                                </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#albumCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Précédent</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#albumCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Suivant</span>
                            </button>
                            <div class="carousel-indicators">
                                @foreach($album->media as $k => $media)
                                <button type="button" data-bs-target="#albumCarousel" data-bs-slide-to="{{$k}}" class="{{ $k == 0 ? 'active' : '' }}" aria-current="{{ $k == 0 ? 'true' : 'false' }}" aria-label="Diapositive {{$k+1}}"></button>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Album Content -->
                        <div class="card-body p-4">
                            <div class="album-description">{!! $album->description !!}</div>
                            
                            <!-- Album Tags -->
                            <div class="mt-4">
                                <span class="badge bg-light text-uib-red me-2">UIB</span>
                                <span class="badge bg-light text-uib-red me-2">Donation</span>
                                <span class="badge bg-light text-uib-red me-2">Communauté</span>
                                <span class="badge bg-light text-uib-red">Tunisie</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('home.albums') }}" class="btn btn-outline-primary">
                            <i class="fa fa-arrow-left me-2"></i>Retour à la galerie
                        </a>
                        <a href="{{ route('home.donate') }}" class="btn btn-primary">
                            <i class="fa fa-hand-holding-heart me-2"></i>Soutenez notre cause
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('javascript')
    <script>
        $(function() {
            // Initialize carousel with swipe support
            const myCarousel = document.querySelector('#albumCarousel');
            const carousel = new bootstrap.Carousel(myCarousel, {
                interval: 5000,
                wrap: true
            });
        });
    </script>
@endsection