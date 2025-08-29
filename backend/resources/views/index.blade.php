@extends('layouts.app-banner')

@section('content')
<div class="w-100 h-100">
    <div class="position-fixed w-100 h-100 z-n1 overflow-hidden">
        <div class="position-absolute w-100 h-100 bg-dark opacity-75 z-2"></div>
        <div id="carouselExampleSlidesOnly" class="carousel slide carousel-fade h-100" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner h-100">
                <div class="carousel-item h-100 active">
                    <img src="{{ asset("images/3.jpg")}}" class="d-block object-fit-cover h-100 w-100" alt="Impact des dons">
                </div>
                <div class="carousel-item h-100">
                    <img src="{{ asset("images/2.jpg")}}" class="d-block object-fit-cover h-100 w-100" alt="Soutien communautaire">
                </div>
                <div class="carousel-item h-100">
                    <img src="{{ asset("images/4.jpg")}}" class="d-block object-fit-cover h-100 w-100" alt="Initiative UIB">
                </div>
            </div>
        </div>
    </div>
    <div class="container d-flex w-100 h-100 p-3 mx-auto flex-column text-white position-relative">
        <header class="mb-auto">
            <div>
                <div class="uib-logo float-md-start">
                    <img src="{{ asset('uib.png') }}" alt="UIB Logo">
                    <div class="uib-logo-text">{{ env('APP_NAME', 'Donation par UIB') }}</div>
                </div>
                <nav class="nav nav-masthead justify-content-center float-md-end">
                    <a class="nav-link fw-bold py-1 px-0
                        @if(Route::is('home.index')) active @endif" aria-current="page" href="{{ route('home.index') }}">Accueil</a>
                    <a class="nav-link fw-bold py-1 px-0
                        @if(Route::is('home.donate')) active @endif" href="{{ route('home.donate') }}">Faire un don</a>
                    <a class="nav-link fw-bold py-1 px-0
                        @if(Route::is('home.about')) active @endif" href="{{ route('home.about') }}">À propos</a>
                    <a class="nav-link fw-bold py-1 px-0
                        @if(Route::is('home.albums')) active @endif" href="{{ route('home.albums') }}">Galerie</a>
                    <a class="nav-link fw-bold py-1 px-0
                        @if(Route::is('home.contact')) active @endif" href="{{ route('home.contact') }}">Contact</a>
                </nav>
            </div>
        </header>

        <main class="px-3 hero-content">
            <h1 class="display-4 fw-bold mb-4">Faites la différence aujourd'hui</h1>
            <p class="lead mb-4">Rejoignez UIB dans notre mission de soutenir les communautés à travers la Tunisie. Votre contribution peut changer des vies et créer un impact durable.</p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="{{ route('home.donate') }}" class="btn btn-lg btn-primary fw-bold">
                    <i class="fa fa-hand-holding-heart me-2"></i>Faire un don
                </a>
                <a href="{{ route('home.about') }}" class="btn btn-lg btn-outline-light fw-bold">
                    <i class="fa fa-info-circle me-2"></i>En savoir plus
                </a>
            </div>
            
            <!-- Impact Counter -->
            <div class="row mt-5">
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="glass-card p-3">
                        <h3 class="text-uib-red">TND 250,000+</h3>
                        <p class="mb-0">Dons collectés</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="glass-card p-3">
                        <h3 class="text-uib-red">1,500+</h3>
                        <p class="mb-0">Vies impactées</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass-card p-3">
                        <h3 class="text-uib-red">25+</h3>
                        <p class="mb-0">Projets communautaires</p>
                    </div>
                </div>
            </div>
        </main>

        <footer class="mt-auto text-white-50">
            <div class="social-icons mb-3">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <p class="mb-0">&copy; {{ now()->format('Y') }} UIB. Tous droits réservés.</p>
        </footer>
    </div>
</div>
@endsection