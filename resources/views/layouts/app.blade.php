<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Clínica Renal') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --hc-primary: #0a66c2;       
            --hc-secondary: #00a896;     
            --hc-bg: #f4f7f6;            
            --hc-card-bg: #ffffff;
            --hc-text: #2b3a42;          
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--hc-bg);
            color: var(--hc-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #0f3057 0%, #164e63 100%); 
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 0.8rem 1rem;
        }

        .navbar-custom .navbar-brand {
            color: #ffffff;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-custom .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 500;
        }

        .navbar-custom .nav-link:hover {
            color: #ffffff !important;
        }

        .user-dropdown {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 0.3rem 1rem !important;
            transition: all 0.3s ease;
        }

        .user-dropdown:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .main-content {
            flex: 1;
            padding-top: 2.5rem;
            padding-bottom: 2.5rem;
        }

        .card {
            border: none !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04) !important;
            background-color: var(--hc-card-bg);
        }

        .card-header {
            background: transparent !important;
            border-bottom: 1px solid rgba(0,0,0,0.05) !important;
            font-weight: 600;
            color: #0f3057;
            padding: 1.2rem 1.5rem !important;
            font-size: 1.1rem;
        }

        .footer-custom {
            background-color: #ffffff;
            border-top: 1px solid rgba(0,0,0,0.05);
            padding: 1rem 0;
            font-size: 0.85rem;
            color: #718096;
            text-align: center;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <div id="app" style="display: flex; flex-direction: column; min-height: 100vh;">
        
        @include('layouts.navigation')

        <main class="main-content">
            @yield('content')
        </main>

        <footer class="footer-custom">
            <div class="container-fluid px-4">
                &copy; {{ date('Y') }} {{ config('app.name', 'Clínica Renal') }} - Unidad de Nefrología y Hemodiálisis.
            </div>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <script>
        toastr.options = {
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "4000",
            "closeButton": true
        };

        @if(session('success')) toastr.success("{{ session('success') }}"); @endif
        @if(session('error')) toastr.error("{{ session('error') }}"); @endif
    </script>

    @stack('scripts')
</body>
</html>