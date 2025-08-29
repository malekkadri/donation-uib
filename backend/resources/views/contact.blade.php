@extends('layouts.app')

@section('css')
<style>
    .contact-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: #f8f9fa;
        color: var(--uib-red);
        font-size: 1.5rem;
        margin-right: 1rem;
        transition: all 0.3s ease;
    }
    .contact-card:hover .contact-icon {
        background-color: var(--uib-red);
        color: white;
        transform: scale(1.1);
    }
    .map-container {
        position: relative;
        overflow: hidden;
        border-radius: 0.5rem;
    }
    .map-container iframe {
        transition: all 0.3s ease;
    }
    .map-container:hover iframe {
        filter: saturate(1.2);
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--uib-red);
        box-shadow: 0 0 0 0.25rem rgba(237, 28, 36, 0.25);
    }
</style>
@endsection

@section('content')
<div class="container">
    <main>
        <!-- Hero Section -->
        <div class="py-5 text-center">
            <h1 class="section-title text-center">Contactez UIB Donation</h1>
            <p class="lead text-muted">
                Nous sommes là pour répondre à toutes vos questions concernant notre plateforme de dons. Contactez-nous via l'un des canaux ci-dessous.
            </p>
        </div>

        <!-- Contact Information Cards -->
        <div class="row mb-5">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100 contact-card shadow-hover">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="contact-icon">
                                <i class="fa fa-map-marker-alt"></i>
                            </div>
                            <h4 class="mb-0">Notre adresse</h4>
                        </div>
                        <p class="text-muted mb-0">
                            Siège UIB,<br>
                            65 Avenue Habib Bourguiba,<br>
                            1000 Tunis, Tunisie
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100 contact-card shadow-hover">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="contact-icon">
                                <i class="fa fa-phone-alt"></i>
                            </div>
                            <h4 class="mb-0">Téléphone</h4>
                        </div>
                        <p class="text-muted mb-2">Bureau principal:</p>
                        <p class="h5 mb-2">+216 71 123 456</p>
                        <p class="text-muted mb-2">Support aux dons:</p>
                        <p class="h5 mb-0">+216 71 789 012</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100 contact-card shadow-hover">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="contact-icon">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <h4 class="mb-0">Email</h4>
                        </div>
                        <p class="text-muted mb-2">Renseignements généraux:</p>
                        <p class="h5 mb-2">info@uib.tn</p>
                        <p class="text-muted mb-2">Support aux dons:</p>
                        <p class="h5 mb-0">donation@uib.tn</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map and Contact Form -->
        <div class="row mb-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h3 class="section-title mb-4">Trouvez-nous</h3>
                <div class="map-container shadow">
                    <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3194.4383825866396!2d10.1792!3d36.8065!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzbCsDQ4JzIzLjQiTiAxMMKwMTAnNDUuMSJF!5e0!3m2!1sen!2stn!4v1620000000000!5m2!1sen!2stn"
                    width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                
                <!-- Business Hours -->
                <div class="card border-0 shadow-sm mt-4 shadow-hover">
                    <div class="card-body p-4">
                        <h4 class="mb-3">
                            <i class="fa fa-clock text-uib-red me-2"></i>Heures d'ouverture
                        </h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-bold">Lundi - Vendredi:</span>
                                    <span>8h00 - 16h00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-bold">Samedi:</span>
                                    <span>8h00 - 12h00</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Dimanche:</span>
                                    <span>Fermé</span>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <div class="alert alert-light mb-0">
                                    <i class="fa fa-info-circle text-uib-red me-2"></i>
                                    <span class="small">Notre équipe de support aux dons est disponible pendant les heures d'ouverture pour vous aider avec toutes vos questions.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <h3 class="section-title mb-4">Contactez-nous</h3>
                <div class="card border-0 shadow-sm shadow-hover">
                    <div class="card-header bg-uib-red text-white py-3">
                        <h5 class="mb-0">
                            <i class="fa fa-paper-plane me-2"></i>Envoyez-nous un message
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('home.contact.submit') }}" id="contactForm">
                            @csrf
                            @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <strong><i class="fa fa-exclamation-circle me-2"></i>Oups!</strong> Veuillez corriger les erreurs ci-dessous:
                                <ul class="mb-0 mt-2">{!! implode('', $errors->all('<li>:message</li>')) !!}</ul>
                            </div>
                            @endif

                            @if (Session::has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <strong><i class="fa fa-exclamation-circle me-2"></i>Oups!</strong> {{ Session::get('error') }}
                            </div>
                            @endif
                            
                            @if (Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <i class="fa fa-check-circle me-2"></i>{{ Session::get('success') }}
                            </div>
                            @endif
                            
                            <div class="mb-3">
                                <label class="form-label">Votre nom</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                    <input type="text" class="form-control required" name="name" placeholder="Entrez votre nom complet" value="{{ old('name') }}">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Adresse email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    <input type="email" class="form-control required" name="email" placeholder="Entrez votre adresse email" value="{{ old('email') }}">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Numéro de téléphone</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                    <input type="text" class="form-control required mobile" name="mobile" placeholder="Entrez votre numéro de téléphone" value="{{ old('mobile') }}">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Message</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-comment"></i></span>
                                    <textarea class="form-control required" name="message" rows="5" placeholder="Écrivez votre message ici...">{{ old('message') }}</textarea>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-paper-plane me-2"></i>Envoyer le message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Social Media Links -->
                <div class="card border-0 shadow-sm mt-4 shadow-hover">
                    <div class="card-body p-4">
                        <h4 class="mb-3">
                            <i class="fa fa-share-alt text-uib-red me-2"></i>Suivez-nous
                        </h4>
                        <div class="d-flex justify-content-between">
                            <a href="#" class="btn btn-outline-dark">
                                <i class="fab fa-facebook-f me-2"></i>Facebook
                            </a>
                            <a href="#" class="btn btn-outline-dark">
                                <i class="fab fa-twitter me-2"></i>Twitter
                            </a>
                            <a href="#" class="btn btn-outline-dark">
                                <i class="fab fa-instagram me-2"></i>Instagram
                            </a>
                            <a href="#" class="btn btn-outline-dark">
                                <i class="fab fa-linkedin-in me-2"></i>LinkedIn
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="row mb-5">
            <div class="col-12">
                <h3 class="section-title text-center mb-4">Questions fréquemment posées</h3>
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Comment puis-je faire un don?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Vous pouvez faire un don via notre site web en visitant la page <a href="{{ route('home.donate') }}">Faire un don</a>. Nous acceptons divers modes de paiement, notamment les cartes de crédit et les virements bancaires.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Mon don est-il déductible d'impôts?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Oui, tous les dons faits à UIB Donation sont déductibles d'impôts en Tunisie. Vous recevrez un reçu pour votre don que vous pourrez utiliser à des fins fiscales.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Comment mon don est-il utilisé?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Votre don soutient diverses initiatives communautaires, notamment l'éducation, la santé et les programmes d'aide sociale. Nous assurons la transparence dans la façon dont les fonds sont alloués et utilisés.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@section('javascript')
<script>
    $(function(){
        $('form').on("submit", function(e){
            e.preventDefault();

            let $form = $(this);
            let has_error = false;
            $.each($form.find("input.required"), function(){
                let $field = $(this);
                if($field.val().trim() == ""){
                    $field.addClass("is-invalid");
                    has_error = true;
                }else{
                    $field.removeClass("is-invalid");
                }
            })

            $.each($form.find("select.required"), function(){
                let $field = $(this);
                if($field.val().trim() == ""){
                    $field.addClass("is-invalid");
                    has_error = true;
                }else{
                    $field.removeClass("is-invalid");
                }
            })

            $.each($form.find("textarea.required"), function(){
                let $field = $(this);
                if($field.val().trim() == ""){
                    $field.addClass("is-invalid");
                    has_error = true;
                }else{
                    $field.removeClass("is-invalid");
                }
            });

            if(has_error){
                toastr.error("Veuillez remplir tous les champs obligatoires.", "Oups!")
                return false;
            }

            $form.find(":submit").prop("disabled",true).html("<i class='fa fa-spin fa-spinner me-2'></i> Envoi en cours...");
            $form.unbind("submit").submit();
        });
    });
</script>
@endsection
