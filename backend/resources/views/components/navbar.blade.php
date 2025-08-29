<nav class="navbar navbar-expand-lg sticky-top shadow-sm bg-white">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home.index') }}">
            <div class="uib-logo">
                <img src="{{ asset('uib.png') }}" alt="UIB Logo">
                <div class="uib-logo-text">{{ env('APP_NAME', 'Donation par UIB') }}</div>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link px-3 @if(Route::is('home.index')) active @endif" aria-current="page" href="{{ route('home.index') }}">
                        <i class="fa fa-home me-1"></i> Accueil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 @if(Route::is('home.donate')) active @endif" href="{{ route('home.donate') }}">
                        <i class="fa fa-hand-holding-heart me-1"></i> Faire un don
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 @if(Route::is('home.about')) active @endif" href="{{ route('home.about') }}">
                        <i class="fa fa-info-circle me-1"></i> À propos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 @if(Route::is('home.albums')) active @endif" href="{{ route('home.albums') }}">
                        <i class="fa fa-images me-1"></i> Galerie
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 @if(Route::is('home.contact')) active @endif" href="{{ route('home.contact') }}">
                        <i class="fa fa-envelope me-1"></i> Contact
                    </a>
                </li>
                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                    <a class="btn btn-primary btn-sm" href="{{ route('home.donate') }}">
                        <i class="fa fa-donate me-1"></i> Faire un don
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>