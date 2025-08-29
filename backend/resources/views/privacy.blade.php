@extends('layouts.app')
@section('css')
<style>
    .privacy-section {
        border-left: 3px solid var(--uib-red);
        padding-left: 1.5rem;
        margin-bottom: 2rem;
    }
    .privacy-icon {
        font-size: 2.5rem;
        color: var(--uib-red);
        margin-bottom: 1rem;
    }
</style>
@endsection
@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home.index') }}" class="text-decoration-none">Accueil</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Politique de confidentialité</li>
                    </ol>
                </nav>
                
                <h1 class="section-title mb-4">Politique de confidentialité</h1>
                <p class="lead text-muted mb-5">Cette politique de confidentialité décrit nos politiques et procédures concernant la collecte et l'utilisation de vos informations lorsque vous utilisez le service de donation UIB. Nous nous engageons à protéger votre vie privée et à garantir que vos données sont traitées de manière responsable.</p>
                
                <div class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <div class="privacy-section">
                            <div class="privacy-icon">
                                <i class="fa fa-book"></i>
                            </div>
                            <h3>Définitions</h3>
                            <div class="row mt-4">
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title text-uib-red">
                                                <i class="fa fa-laptop me-2"></i>Appareil
                                            </h5>
                                            <p class="card-text">Tout appareil pouvant accéder au Service tel qu'un ordinateur, un téléphone portable ou une tablette numérique.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title text-uib-red">
                                                <i class="fa fa-cookie-bite me-2"></i>Cookies
                                            </h5>
                                            <p class="card-text">Petits fichiers placés sur votre appareil contenant les détails de votre historique de navigation sur notre site web, entre autres utilisations.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title text-uib-red">
                                                <i class="fa fa-user-shield me-2"></i>Données personnelles
                                            </h5>
                                            <p class="card-text">Toute information relative à une personne identifiée ou identifiable.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="privacy-section">
                            <div class="privacy-icon">
                                <i class="fa fa-shield-alt"></i>
                            </div>
                            <h3>Utilisation de vos données personnelles</h3>
                            <p>Vos données personnelles telles que votre nom, numéro et email ne seront partagées avec aucun tiers dans aucune condition. Vos coordonnées bancaires que vous fournissez lors du paiement sont cryptées de bout en bout et ne sont même pas utilisées par notre fiducie. Votre identité peut être partagée avec les autorités gouvernementales si demandée à des fins d'impôt sur le revenu.</p>
                            
                            <h5 class="mt-4 mb-3">La Fiducie peut utiliser les données personnelles aux fins suivantes:</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-transparent">
                                    <i class="fa fa-check-circle text-uib-red me-2"></i>
                                    Pour vous remercier de votre contribution par téléphone ou email
                                </li>
                                <li class="list-group-item bg-transparent">
                                    <i class="fa fa-check-circle text-uib-red me-2"></i>
                                    Pour vous contacter concernant votre don ou pour des mises à jour importantes
                                </li>
                                <li class="list-group-item bg-transparent">
                                    <i class="fa fa-check-circle text-uib-red me-2"></i>
                                    Pour vous fournir des informations sur nos initiatives et leur impact
                                </li>
                                <li class="list-group-item bg-transparent">
                                    <i class="fa fa-check-circle text-uib-red me-2"></i>
                                    Pour améliorer notre plateforme de dons et l'expérience utilisateur
                                </li>
                            </ul>
                        </div>
                        
                        <div class="privacy-section">
                            <div class="privacy-icon">
                                <i class="fa fa-lock"></i>
                            </div>
                            <h3>Sécurité des données</h3>
                            <p>Nous mettons en œuvre des mesures de sécurité appropriées pour protéger vos données personnelles contre tout accès, altération, divulgation ou destruction non autorisés. Toutes les transactions sont traitées via des passerelles de paiement sécurisées qui respectent les normes internationales de sécurité.</p>
                        </div>
                        
                        <div class="privacy-section mb-0">
                            <div class="privacy-icon">
                                <i class="fa fa-question-circle"></i>
                            </div>
                            <h3>Contactez-nous</h3>
                            <p>Si vous avez des questions concernant cette politique de confidentialité, vous pouvez nous contacter à:</p>
                            <div class="alert alert-light">
                                <p class="mb-1"><i class="fa fa-envelope text-uib-red me-2"></i>Email: privacy@uib.tn</p>
                                <p class="mb-1"><i class="fa fa-phone text-uib-red me-2"></i>Téléphone: +216 71 123 456</p>
                                <p class="mb-0"><i class="fa fa-map-marker-alt text-uib-red me-2"></i>Adresse: 65 Avenue Habib Bourguiba, 1000 Tunis, Tunisie</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    <a href="{{ route('home.index') }}" class="btn btn-outline-primary">
                        <i class="fa fa-arrow-left me-2"></i>Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
@endsection