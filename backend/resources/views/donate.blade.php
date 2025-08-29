@extends('layouts.app')

@section('css')
    <style>
        .donation-icon {
            font-size: 3rem;
            color: var(--uib-red);
            margin-bottom: 1rem;
        }
        .donation-quote {
            font-style: italic;
            position: relative;
            padding: 0 1.5rem;
        }
        .donation-quote::before, .donation-quote::after {
            content: '';
            position: absolute;
            width: 3px;
            height: 100%;
            background-color: var(--uib-light-gray);
            top: 0;
        }
        .donation-quote::before {
            left: 0;
        }
        .donation-quote::after {
            right: 0;
        }
        .amount-input {
            font-size: 1.25rem;
            font-weight: 600;
            height: 3rem;
        }
        .donation-card {
            transition: all 0.3s ease;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .donation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
        }
        .test-cc-badge {
            font-family: monospace;
            letter-spacing: 1px;
        }
        .leaderboard-highlight {
            background-color: rgba(237, 28, 36, 0.05);
        }
        .hidden {
            display: none;
        }
        #payment-message {
            color: rgb(105, 115, 134);
            font-size: 16px;
            line-height: 20px;
            padding-top: 12px;
            text-align: center;
        }
        #payment-element {
            margin-bottom: 24px;
        }
        button[type=submit] {
            background: var(--uib-red);
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            border-radius: 0.375rem;
            border: 0;
            padding: 12px 16px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: block;
            transition: all 0.2s ease;
            box-shadow: 0px 4px 5.5px 0px rgba(0, 0, 0, 0.07);
            width: 100%;
        }
        button[type=submit]:hover {
            background-color: var(--uib-dark-red);
        }
        button[type=submit]:disabled {
            opacity: 0.5;
            cursor: default;
        }
        .spinner,
        .spinner:before,
        .spinner:after {
            border-radius: 50%;
        }
        .spinner {
            color: #ffffff;
            font-size: 22px;
            text-indent: -99999px;
            margin: 0px auto;
            position: relative;
            width: 20px;
            height: 20px;
            box-shadow: inset 0 0 0 2px;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
        }
        .spinner:before,
        .spinner:after {
            position: absolute;
            content: "";
        }
        .spinner:before {
            width: 10.4px;
            height: 20.4px;
            background: var(--uib-red);
            border-radius: 20.4px 0 0 20.4px;
            top: -0.2px;
            left: -0.2px;
            -webkit-transform-origin: 10.4px 10.2px;
            transform-origin: 10.4px 10.2px;
            -webkit-animation: loading 2s infinite ease 1.5s;
            animation: loading 2s infinite ease 1.5s;
        }
        .spinner:after {
            width: 10.4px;
            height: 10.2px;
            background: var(--uib-red);
            border-radius: 0 10.2px 10.2px 0;
            top: -0.1px;
            left: 10.2px;
            -webkit-transform-origin: 0px 10.2px;
            transform-origin: 0px 10.2px;
            -webkit-animation: loading 2s infinite ease;
            animation: loading 2s infinite ease;
        }
        @keyframes loading {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
    </style>
    <script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')
    <div class="container">
        <!-- Hero Section -->
        <div class="py-5 text-center">
            <div class="donation-icon">
                <i class="fa fa-hand-holding-heart"></i>
            </div>
            <h1 class="section-title text-center">Faire un don</h1>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="donation-quote my-4">
                        <p class="lead mb-0">"Personne n'est jamais devenu pauvre en donnant."</p>
                        <p class="text-muted">— Anne Frank</p>
                    </div>
                    <p class="text-muted">Votre contribution, aussi petite soit-elle, peut faire une différence significative dans la vie de quelqu'un. Rejoignez UIB dans notre mission de soutenir les communautés à travers la Tunisie.</p>
                </div>
            </div>
        </div>

        <!-- Donation Options -->
        <div class="row mb-5">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100 donation-card">
                    <div class="card-body text-center p-4">
                        <div class="bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fa fa-graduation-cap fa-2x text-uib-red"></i>
                        </div>
                        <h4 class="mb-3">Éducation</h4>
                        <p class="text-muted">Soutenez des programmes éducatifs pour les enfants défavorisés, en leur fournissant les outils dont ils ont besoin pour réussir.</p>
                        <a href="#donation-form" class="btn btn-outline-primary mt-3">Donner pour l'éducation</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100 donation-card">
                    <div class="card-body text-center p-4">
                        <div class="bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fa fa-heartbeat fa-2x text-uib-red"></i>
                        </div>
                        <h4 class="mb-3">Santé</h4>
                        <p class="text-muted">Aidez à fournir une assistance médicale et des services de santé à ceux qui ne peuvent pas se les permettre.</p>
                        <a href="#donation-form" class="btn btn-outline-primary mt-3">Donner pour la santé</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100 donation-card">
                    <div class="card-body text-center p-4">
                        <div class="bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fa fa-home fa-2x text-uib-red"></i>
                        </div>
                        <h4 class="mb-3">Communauté</h4>
                        <p class="text-muted">Soutenez des projets de développement communautaire qui améliorent les infrastructures et créent des opportunités.</p>
                        <a href="#donation-form" class="btn btn-outline-primary mt-3">Donner pour la communauté</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Donation Form -->
        <div class="row g-5 mb-5" id="donation-form">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow">
                    <div class="card-header bg-uib-red text-white py-3">
                        <h3 class="mb-0">
                            <i class="fa fa-heart me-2"></i>Informations du donateur
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <!-- Display a payment form -->
                        <form id="payment-form" method="POST" action="{{ route('process.checkout') }}" class="mt-0">
                            @csrf
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                    <strong><i class="fa fa-exclamation-circle me-2"></i>Oups!</strong> Veuillez corriger les erreurs ci-dessous:
                                    <ul class="mb-0 mt-2">{!! implode('', $errors->all('<li>:message</li>')) !!}</ul>
                                </div>
                            @endif

                            @if (Session::has('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                    <strong><i class="fa fa-exclamation-circle me-2"></i>Oups!</strong> {{ Session::get('error') }}
                                </div>
                            @endif
                            @if (Session::has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                    <i class="fa fa-check-circle me-2"></i>{{ Session::get('success') }}
                                </div>
                            @endif

                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-info-circle fa-2x me-3 text-primary"></i>
                                    <div>
                                        <strong>Détails de carte de test:</strong>
                                        <div class="mt-2 d-flex flex-wrap gap-2">
                                            <span class="badge bg-light text-dark test-cc-badge">4111 1111 1111 1111</span>
                                            <span class="badge bg-light text-dark test-cc-badge">{{ now()->addMonths(rand(15, 50))->format('m/y') }}</span>
                                            <span class="badge bg-light text-dark test-cc-badge">{{ rand(100, 999) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Personal Information -->
                            <h5 class="mb-3 mt-4">
                                <i class="fa fa-user-circle me-2 text-uib-red"></i>Informations personnelles
                            </h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Prénom</label>
                                    <input type="text" class="form-control required" name="first_name"
                                        value="{{ old('first_name') }}" placeholder="Prénom">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nom</label>
                                    <input type="text" class="form-control required" name="last_name"
                                        value="{{ old('last_name') }}" placeholder="Nom">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Adresse email</label>
                                    <input type="text" class="form-control required" name="email"
                                        value="{{ old('email') }}" placeholder="Adresse email">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Numéro de téléphone</label>
                                    <input type="text" class="form-control optional mobile" name="mobile"
                                        value="{{ old('mobile') }}" placeholder="Numéro de téléphone">
                                </div>
                            </div>

                            <!-- Address Information -->
                            <h5 class="mb-3">
                                <i class="fa fa-map-marker-alt me-2 text-uib-red"></i>Informations d'adresse
                            </h5>
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label class="form-label">Adresse</label>
                                    <input type="text" class="form-control optional" name="street_address"
                                        value="{{ old('street_address') }}" placeholder="Adresse">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Pays</label>
                                    <input type="hidden" name="country_name" value="{{ old('country_name') }}">
                                    <select class="form-select required" name="country">
                                        @if (old('country') && old('country_name'))
                                            <option value="{{ old('country') }}" selected>{{ old('country_name') }}
                                            </option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">État/Province</label>
                                    <input type="hidden" name="state_name" value="{{ old('state_name') }}">
                                    <select class="form-select required" name="state">
                                        @if (old('state') && old('state_name'))
                                            <option value="{{ old('state') }}" selected>{{ old('state_name') }}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Ville</label>
                                    <input type="hidden" name="city_name" value="{{ old('city_name') }}">
                                    <select class="form-select required" name="city">
                                        @if (old('city') && old('city_name'))
                                            <option value="{{ old('city') }}" selected>{{ old('city_name') }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <!-- Donation Amount -->
                            <h5 class="mb-3">
                                <i class="fa fa-donate me-2 text-uib-red"></i>Montant du don
                            </h5>
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label class="form-label">Montant que vous souhaitez donner <small class="text-muted">(Minimum
                                        TND {{number_format(env('MIN_DONATION_AMOUNT'),3,".",",")}})</small></label>
                                    <div class="input-group">
                                        <span class="input-group-text">TND</span>
                                        <input type="number" class="form-control amount-input required"
                                            name="amount" value="{{ old('amount') }}"
                                            placeholder="Entrez le montant du don" min="{{env('MIN_DONATION_AMOUNT')}}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="add_to_leaderboard"
                                            @if (old('add_to_leaderboard') == 'yes') checked="checked" @endif value="yes"
                                            id="flexSwitchCheckDefault" role="button">
                                        <label class="form-check-label" for="flexSwitchCheckDefault" role="button">
                                            Afficher votre nom sur le <a href="{{ route('home.leaderboard') }}" target="_blank">Tableau des donateurs</a>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Button -->
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fa fa-heart me-2"></i>Finaliser le don
                                </button>
                            </div>
                            <p class="text-center text-muted small mt-3">
                                <i class="fa fa-lock me-1"></i>Vos informations de paiement sont sécurisées. Nous utilisons le cryptage pour protéger vos données.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leaderboard Section -->
        @include('components.leaderboard')

        <!-- Testimonials Section -->
        <div class="row my-5">
            <div class="col-12">
                <h2 class="section-title text-center mb-4">Ce que disent nos donateurs</h2>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card border-0 shadow-sm h-100 shadow-hover">
                            <div class="card-body p-4">
                                <div class="d-flex mb-3">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <i class="fa fa-user text-uib-red"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">Ahmed Ben Ali</h5>
                                        <p class="text-muted mb-0">Donateur régulier</p>
                                    </div>
                                </div>
                                <p class="mb-0">
                                    <i class="fa fa-quote-left text-uib-red me-2"></i>
                                    Je fais des dons aux initiatives de l'UIB depuis des années. La transparence et l'impact qu'ils fournissent sont inégalés.
                                    <i class="fa fa-quote-right text-uib-red ms-2"></i>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card border-0 shadow-sm h-100 shadow-hover">
                            <div class="card-body p-4">
                                <div class="d-flex mb-3">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <i class="fa fa-user text-uib-red"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">Fatima Mansour</h5>
                                        <p class="text-muted mb-0">Première donation</p>
                                    </div>
                                </div>
                                <p class="mb-0">
                                    <i class="fa fa-quote-left text-uib-red me-2"></i>
                                    Le processus de don était simple et sécurisé. J'apprécie la façon dont UIB me tient informée de l'impact de ma contribution.
                                    <i class="fa fa-quote-right text-uib-red ms-2"></i>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card border-0 shadow-sm h-100 shadow-hover">
                            <div class="card-body p-4">
                                <div class="d-flex mb-3">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <i class="fa fa-user text-uib-red"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">Mohamed Karim</h5>
                                        <p class="text-muted mb-0">Donateur mensuel</p>
                                    </div>
                                </div>
                                <p class="mb-0">
                                    <i class="fa fa-quote-left text-uib-red me-2"></i>
                                    La mise en place d'un don mensuel était facile. C'est agréable de savoir que je soutiens constamment des causes importantes dans notre communauté.
                                    <i class="fa fa-quote-right text-uib-red ms-2"></i>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $(function() {
            // Form validation
            $(document).on('submit', '#payment-form', function(e) {
                let form = $(this);

                $(".required").removeClass("is-invalid");
                $(".required").siblings(".select2").find(".select2-selection").removeClass("border-danger");

                if ($("input.required").val() == "" || $("select.required").val() == null) {
                    toastr.error("Veuillez remplir tous les champs obligatoires.", "Oups!");
                    $(".required").each(function() {
                        if ($(this).val() == "" || $(this).val() == null) {
                            $(this).addClass("is-invalid");
                            $(this).siblings(".select2").find(".select2-selection").addClass(
                                "border-danger");
                        }
                    })
                    return false
                }
                form.find('[type=submit]').prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Traitement en cours...');
            });

            // Country select
            $("[name=country]").each(function() {
                let $this = $(this)
                $this.select2({
                    placeholder: 'Sélectionnez un pays',
                    width: '100%',
                    allowClear: true,
                    ajax: {
                        url: '{{ route('find.countries') }}',
                        dataType: 'json',
                        delay: 300,
                        data: function(params) {
                            return {
                                term: params.term || '',
                                page: params.page || 1,
                            }
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: data.pagination.more
                                }
                            };
                        },
                        cache: false,
                    }
                }).on("change", function(e) {
                    if ($this.val()) {
                        let text = $this.find('option:selected').text();
                        $("[name=country_name]").val(text);
                    } else {
                        $("[name=country_name]").val(null);
                    }
                    $("[name=state]").val(null).trigger("change")
                    $("[name=city]").val(null).trigger("change")
                });
            }).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });

            // State select
            $("[name=state]").each(function() {
                let $this = $(this)
                $this.select2({
                    placeholder: 'Sélectionnez un état/province',
                    width: '100%',
                    allowClear: true,
                    ajax: {
                        url: '{{ route('find.states') }}',
                        dataType: 'json',
                        delay: 300,
                        data: function(params) {
                            return {
                                term: params.term || '',
                                page: params.page || 1,
                                country_id: $("[name=country]").val(),
                            }
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: data.pagination.more
                                }
                            };
                        },
                        cache: false,
                    }
                }).on("select2:opening", function(e) {
                    let country = $("[name=country]").val();
                    if (country == null) {
                        setTimeout(() => {
                            $this.select2('close');
                            $this.val(null).trigger("change");
                        }, 100);
                        toastr.error("Veuillez d'abord sélectionner un pays.", "Oups!");
                    }
                }).on("change", function(e) {
                    if ($this.val()) {
                        let text = $this.find('option:selected').text();
                        $("[name=state_name]").val(text);
                    } else {
                        $("[name=state_name]").val(null);
                    }
                    $("[name=city]").val(null).trigger("change");
                }).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });
            });

            // City select
            $("[name=city]").each(function() {
                let $this = $(this)
                $this.select2({
                    placeholder: 'Sélectionnez une ville',
                    width: '100%',
                    allowClear: true,
                    ajax: {
                        url: '{{ route('find.cities') }}',
                        dataType: 'json',
                        delay: 300,
                        data: function(params) {
                            return {
                                term: params.term || '',
                                page: params.page || 1,
                                country_id: $("[name=country]").val(),
                                state_id: $("[name=state]").val(),
                            }
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: data.pagination.more
                                }
                            };
                        },
                        cache: false,
                    }
                }).on("select2:opening", function(e) {
                    let country = $("[name=country]").val();
                    let state = $("[name=state]").val();
                    if (country == null || state == null) {
                        setTimeout(() => {
                            $this.select2('close');
                            $this.val(null).trigger("change");
                        }, 100);
                        toastr.error("Veuillez d'abord sélectionner un pays et un état/province.", "Oups!");
                    }
                }).on('change', function() {
                    if ($this.val()) {
                        let text = $this.find('option:selected').text();
                        $("[name=city_name]").val(text);
                    } else {
                        $("[name=city_name]").val(null);
                    }
                }).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });
            });
            
            // Smooth scroll to donation form
            $('a[href="#donation-form"]').click(function(e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $($(this).attr('href')).offset().top - 100
                }, 500);
            });
        });
    </script>
@endsection