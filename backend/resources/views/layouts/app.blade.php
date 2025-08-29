<!doctype html>
<html lang="fr" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Donation par UIB - Soutenez notre cause">
    <meta name="author" content="UIB">
    <meta name="generator" content="Hugo 0.108.0">
    <title>{{ env("APP_NAME", "Donation par UIB") }}</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/cover/">
    <link href="{{ asset('vendor/fontawesome-5.15.4-web/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --uib-red: #ed1c24;
            --uib-dark-red: #c01017;
            --uib-black: #000000;
            --uib-light-gray: #f8f9fa;
            --uib-gray: #6c757d;
            --uib-dark-gray: #343a40;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .container {
            max-width: 1140px;
        }
        
        /* Height and width utilities */
        .h-50px{ height: 50px; }
        .h-100px{ height: 100px; }
        .h-150px{ height: 150px; }
        .h-200px{ height: 200px; }
        .h-250px{ height: 250px; }
        .h-300px{ height: 300px; }
        .h-400px{ height: 400px; }
        .h-500px{ height: 500px; }
        .w-50px{ width: 50px; }
        .w-100px{ width: 100px; }
        .w-150px{ width: 150px; }
        .w-200px{ width: 200px; }
        .w-250px{ width: 250px; }
        .w-300px{ width: 300px; }
        .w-400px{ width: 400px; }
        .w-500px{ width: 500px; }
        
        /* Select2 styling */
        .select2-container .select2-selection{
            height: 38px;
            border: var(--bs-border-width) solid var(--bs-border-color);
            border-radius: 0.375rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered{
            line-height: 38px;
            padding-left: 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow{
            height: 38px;
            right: 8px;
        }
        .select2-dropdown {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple,
        .select2-container--default .select2-selection--multiple {
            height: auto!important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--uib-red);
        }
        
        /* Text styles */
        .text-italic{
            font-style: italic;
        }
        
        /* UIB Theme Colors */
        .bg-uib-red {
            background-color: var(--uib-red) !important;
        }
        .bg-uib-black {
            background-color: var(--uib-black) !important;
        }
        .text-uib-red {
            color: var(--uib-red) !important;
        }
        .text-uib-black {
            color: var(--uib-black) !important;
        }
        .border-uib-red {
            border-color: var(--uib-red) !important;
        }
        .border-uib-black {
            border-color: var(--uib-black) !important;
        }
        
        /* Override Bootstrap theme colors */
        .btn-primary {
            background-color: var(--uib-red);
            border-color: var(--uib-red);
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: var(--uib-dark-red) !important;
            border-color: var(--uib-dark-red) !important;
        }
        .btn-outline-primary {
            color: var(--uib-red);
            border-color: var(--uib-red);
        }
        .btn-outline-primary:hover, .btn-outline-primary:focus, .btn-outline-primary:active {
            background-color: var(--uib-red) !important;
            border-color: var(--uib-red) !important;
        }
        .text-primary {
            color: var(--uib-red) !important;
        }
        .nav-link {
            color: var(--uib-dark-gray);
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            color: var(--uib-red);
        }
        .nav-link.active {
            color: var(--uib-red) !important;
            font-weight: 600;
        }
        .page-link {
            color: var(--uib-red);
        }
        .page-item.active .page-link {
            background-color: var(--uib-red);
            border-color: var(--uib-red);
        }
        
        /* Card styling */
        .card {
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .card-header {
            font-weight: 600;
        }
        
        /* Button styling */
        .btn {
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        /* Form controls */
        .form-control, .form-select {
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--uib-red);
            box-shadow: 0 0 0 0.25rem rgba(237, 28, 36, 0.25);
        }
        
        /* Custom section styling */
        .section-title {
            position: relative;
            margin-bottom: 2rem;
            font-weight: 700;
        }
        .section-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -0.5rem;
            width: 50px;
            height: 3px;
            background-color: var(--uib-red);
        }
        .section-title.text-center:after {
            left: 50%;
            transform: translateX(-50%);
        }
        
        /* Footer styling */
        footer {
            background-color: var(--uib-light-gray);
        }
        
        /* UIB Logo */
        .uib-logo {
            display: flex;
            align-items: center;
        }
        .uib-logo img {
            height: 50px;
            width: auto;
            margin-right: 10px;
        }
        .uib-logo-text {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--uib-black);
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .section-title {
                font-size: 1.75rem;
            }
            .uib-logo img {
                height: 40px;
            }
        }
        
        /* Glass effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Gradient backgrounds */
        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--uib-red) 0%, var(--uib-dark-red) 100%);
        }
        
        /* Improved shadows */
        .shadow-hover {
            transition: all 0.3s ease;
        }
        .shadow-hover:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }
    </style>
    @yield('css')

</head>

<body class="bg-light">

    <x-navbar />

    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show sticky-top rounded-0 py-1" role="alert">
            {{ Session::get('error') }}
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="animate-fade-in">
        @yield('content')
    </div>

    <footer class="py-5 mt-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-4 text-md-start mb-4 mb-md-0">
                    <div class="uib-logo mb-3">
                        <img src="{{ asset('uib.png') }}" alt="UIB Logo">
                        <div class="uib-logo-text">Donation par UIB</div>
                    </div>
                    <p class="text-muted">Soutenir les communautés grâce à vos généreuses contributions depuis 2023.</p>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-uib-black mb-3">Contact</h5>
                    <p class="text-muted mb-1"><i class="fa fa-map-marker-alt me-2 text-uib-red"></i>65 Avenue Habib Bourguiba, 1000 Tunis, Tunisie</p>
                    <p class="text-muted mb-1"><i class="fa fa-phone-alt me-2 text-uib-red"></i>+216 71 123 456</p>
                    <p class="text-muted mb-1"><i class="fa fa-envelope me-2 text-uib-red"></i>contact@uib.tn</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <h5 class="text-uib-black mb-3">Suivez-nous</h5>
                    <div class="mb-3">
                        <a href="#" class="btn btn-outline-dark btn-sm rounded-circle me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-outline-dark btn-sm rounded-circle me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn btn-outline-dark btn-sm rounded-circle me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="btn btn-outline-dark btn-sm rounded-circle"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                    <p class="text-muted">Abonnez-vous à notre newsletter</p>
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Votre email">
                        <button class="btn btn-primary" type="button">S'abonner</button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 text-muted">&copy; {{ now()->format('Y') }} UIB. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item"><a href="{{ route('home.privacy-policy') }}" class="text-muted text-decoration-none">Politique de confidentialité</a></li>
                        <li class="list-inline-item"><a href="#" class="text-muted text-decoration-none">Conditions d'utilisation</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        }
        $(function(){
            // Mobile number validation
            $("input.mobile").on("keyup", function(){
                let str = $(this).val();
                str = str.replace(/[^0-9\s]/gi, '').replace(/[_\s]/g, '');
                str = str.length > 10 ? str.substr(0,10) : str;
                $(this).val(str);
            });

            // Form field labels
            $("input.required, select.required, textarea.required").parent().find("label").append("<span class='text-danger'> *</span>");
            $("input.optional, select.optional, textarea.optional").parent().find("label").append("<small class='text-muted'> (optionnel)</small>");
            
            // Initialize tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
            
            // Back to top button
            $(window).scroll(function() {
                if ($(this).scrollTop() > 300) {
                    $('#back-to-top').fadeIn();
                } else {
                    $('#back-to-top').fadeOut();
                }
            });
            $('#back-to-top').click(function() {
                $('html, body').animate({scrollTop: 0}, 800);
                return false;
            });
        });
    </script>
    @yield('javascript')
    
    <!-- Back to top button -->
    <a id="back-to-top" href="#" class="btn btn-primary btn-sm position-fixed rounded-circle" style="display: none; bottom: 20px; right: 20px; z-index: 99;">
        <i class="fa fa-arrow-up"></i>
    </a>
</body>

</html>