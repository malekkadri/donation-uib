@extends('layouts.app')


@section('content')
    <main role="main">
        <div class="container mt-5">
            <div class="text-center mb-5">
                <h1 class="section-title text-center">À propos de Donation par UIB</h1>
                <p class="lead text-muted">Union Internationale de Banques (UIB) s'engage à soutenir les communautés à travers notre plateforme de dons.</p>
            </div>
            
            <!-- Team members section -->
            <div class="row mb-5">
                <div class="col-12">
                    <h2 class="section-title text-center mb-5">Notre Équipe</h2>
                </div>
                @foreach ($member as $person)
                    <div class="col-md-4 mb-4">
                        <div class="card border-0 shadow-sm h-100 shadow-hover">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <div class="rounded-circle mx-auto shadow" style="width:150px; height:150px; background:url({{ asset('images/members/'.$person->image) }}) center no-repeat; background-size:cover; border: 3px solid #f8f9fa;" ></div>
                                </div>
                                <h4 class="fw-bold mb-1 text-uib-black">{{ $person->name }}</h4>
                                <p class="text-uib-red mb-3">{{ $person->designation }}</p>
                                <div class="bg-light p-3 rounded">
                                    <p class="text-italic fw-light mb-0">
                                        <i class="fa fa-quote-left text-uib-red me-2"></i>
                                        {{ $person->quote }}
                                        <i class="fa fa-quote-right text-uib-red ms-2"></i>
                                    </p>
                                </div>
                                <div class="mt-3">
                                    <a href="#" class="btn btn-outline-dark btn-sm rounded-circle mx-1"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="#" class="btn btn-outline-dark btn-sm rounded-circle mx-1"><i class="fab fa-twitter"></i></a>
                                    <a href="#" class="btn btn-outline-dark btn-sm rounded-circle mx-1"><i class="fa fa-envelope"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- About UIB section -->
            <div class="row align-items-center my-5 py-5 border-top border-bottom">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h2 class="section-title">À propos d'UIB</h2>
                    <p class="lead">Union Internationale de Banques (UIB) est une institution financière de premier plan en Tunisie, engagée dans l'excellence des services bancaires et le développement communautaire.</p>
                    <p>Notre plateforme de dons vise à soutenir diverses causes sociales et à avoir un impact positif dans nos communautés. Avec des décennies d'expérience dans le secteur financier, UIB apporte confiance et transparence aux dons caritatifs.</p>
                    <div class="d-flex align-items-center mt-4">
                        <div class="me-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fa fa-check-circle text-uib-red me-2"></i>
                                <span class="fw-bold">Fondée en</span>
                            </div>
                            <p class="ms-4">1963</p>
                        </div>
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fa fa-check-circle text-uib-red me-2"></i>
                                <span class="fw-bold">Agences</span>
                            </div>
                            <p class="ms-4">144+ à l'échelle nationale</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <img class="img-fluid rounded shadow" src="{{ asset('images/1.jpg') }}" alt="Bâtiment UIB">
                </div>
            </div>

            <!-- Mission section -->
            <div class="row align-items-center my-5 py-5">
                <div class="col-md-6 order-md-2 mb-4 mb-md-0">
                    <h2 class="section-title">Notre Mission</h2>
                    <p class="lead">À l'UIB, nous croyons au pouvoir de redonner. Notre plateforme de dons connecte des donateurs généreux avec des causes dignes.</p>
                    <p>Nous assurons la transparence et l'impact dans chaque contribution. Nous nous consacrons à créer un changement positif en Tunisie et au-delà grâce à des partenariats stratégiques avec des organisations locales et internationales.</p>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light rounded-circle p-2 me-3">
                                    <i class="fa fa-heart text-uib-red"></i>
                                </div>
                                <span>Transparence</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light rounded-circle p-2 me-3">
                                    <i class="fa fa-hands-helping text-uib-red"></i>
                                </div>
                                <span>Communauté</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light rounded-circle p-2 me-3">
                                    <i class="fa fa-shield-alt text-uib-red"></i>
                                </div>
                                <span>Sécurité</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light rounded-circle p-2 me-3">
                                    <i class="fa fa-globe text-uib-red"></i>
                                </div>
                                <span>Impact Global</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 order-md-1">
                    <img class="img-fluid rounded shadow" src="{{ asset('images/2.jpg') }}" alt="Soutien communautaire">
                </div>
            </div>

            <!-- Join Our Cause section -->
            <div class="row align-items-center my-5 py-5 border-top">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h2 class="section-title">Rejoignez Notre Cause</h2>
                    <p class="lead">Votre contribution, aussi petite soit-elle, peut faire une différence significative dans la vie de quelqu'un.</p>
                    <p>Rejoignez UIB dans notre mission de soutenir l'éducation, la santé et les initiatives de développement communautaire à travers la Tunisie. Ensemble, nous pouvons construire un avenir meilleur pour tous.</p>
                    <a href="{{ route('home.donate') }}" class="btn btn-primary mt-3">
                        <i class="fa fa-hand-holding-heart me-2"></i>Faire un don
                    </a>
                </div>
                <div class="col-md-6">
                    <img class="img-fluid rounded shadow" src="{{ asset('images/3.jpg') }}" alt="Impact des dons">
                </div>
            </div>
        </div>

        <!-- UIB Information section -->
        <div class="bg-light py-5 mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <div class="card border-0 shadow-sm h-100 shadow-hover">
                            <div class="card-body p-4">
                                <h3 class="text-uib-red mb-4">
                                    <i class="fa fa-building me-2"></i>Siège UIB
                                </h3>
                                <div class="d-flex mb-3">
                                    <i class="fa fa-map-marker-alt text-uib-red mt-1 me-3"></i>
                                    <p class="mb-0">65 Avenue Habib Bourguiba<br>1000 Tunis, Tunisie</p>
                                </div>
                                <div class="d-flex mb-3">
                                    <i class="fa fa-phone-alt text-uib-red mt-1 me-3"></i>
                                    <p class="mb-0">+216 71 123 456</p>
                                </div>
                                <div class="d-flex">
                                    <i class="fa fa-envelope text-uib-red mt-1 me-3"></i>
                                    <p class="mb-0">contact@uib.tn</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100 shadow-hover">
                            <div class="card-body p-4">
                                <h3 class="text-uib-red mb-4">
                                    <i class="fa fa-clock me-2"></i>Heures d'ouverture
                                </h3>
                                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                    <span class="fw-bold">Lundi - Vendredi</span>
                                    <span>8h00 - 16h00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                    <span class="fw-bold">Samedi</span>
                                    <span>8h00 - 12h00</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Dimanche</span>
                                    <span>Fermé</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="#" id="back-to-top-btn" class="btn btn-outline-primary">
                <i class="fa fa-arrow-up me-2"></i>Retour en haut
            </a>
        </div>
    </main>
@endsection
@section('javascript')
    <script>
        $(function() {
            $('#back-to-top-btn').click(function(e) {
                e.preventDefault();
                $('html, body').animate({scrollTop: 0}, 800);
            });
        });
    </script>
@endsection