<!doctype html>
<html lang="fr" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Donation par UIB - Soutenez notre cause">
    <meta name="author" content="UIB">
    <meta name="generator" content="Hugo 0.108.0">
    <title>Donation par UIB</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/cover/">
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-5.15.4-web/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --uib-red: #ed1c24;
            --uib-dark-red: #c01017;
            --uib-black: #000000;
        }
        
        /*
        * Globals
        */
        body {
            font-family: 'Poppins', sans-serif;
            text-shadow: 0 .05rem .1rem rgba(0, 0, 0, .5);
            box-shadow: inset 0 0 5rem rgba(0, 0, 0, .5);
            background-color: var(--uib-black);
        }

        .cover-container {
            max-width: 42em;
        }

        /*
        * Header
        */
        .nav-masthead .nav-link {
            color: rgba(255, 255, 255, .75);
            border-bottom: .25rem solid transparent;
            transition: all 0.3s ease;
            font-weight: 500;
            padding: 0.5rem 1rem;
        }

        .nav-masthead .nav-link:hover,
        .nav-masthead .nav-link:focus {
            color: #fff;
            border-bottom-color: rgba(255, 255, 255, .5);
        }

        .nav-masthead .nav-link+.nav-link {
            margin-left: 0.5rem;
        }

        .nav-masthead .active {
            color: #fff;
            border-bottom-color: var(--uib-red);
        }

        .btn-primary {
            background-color: var(--uib-red);
            border-color: var(--uib-red);
        }
        
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--uib-dark-red);
            border-color: var(--uib-dark-red);
        }
        
        .btn-outline-light:hover {
            color: var(--uib-red);
        }
        
        /* UIB Logo */
        .uib-logo {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .uib-logo img {
            height: 60px;
            width: auto;
            margin-right: 15px;
        }
        
        .uib-logo-text {
            color: #fff;
            font-size: 2.5rem;
            font-weight: bold;
        }
        
        /* Hero content */
        .hero-content {
            animation: fadeIn 1.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Background slider */
        .carousel-item {
            transition: transform 2s ease, opacity .5s ease-out;
        }
        
        /* Social icons */
        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            margin: 0 5px;
            transition: all 0.3s ease;
        }
        
        .social-icons a:hover {
            background-color: var(--uib-red);
            transform: translateY(-3px);
        }
        
        /* Glass effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
    </style>

</head>

<body class="d-flex h-100 text-center">

    @yield('content')

    <script src="{{ asset("bootstrap/js/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset("vendor/fontawesome-5.15.4-web/js/all.min.js") }}"></script>
</body>

</html>