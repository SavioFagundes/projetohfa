<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Fallback / shipped CSS (styles for tarefas) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- auth.css is intentionally not loaded on authenticated pages to keep internal UI consistent -->
    </head>
    <body class="font-sans antialiased" id="app-body">
        <style>
            :root{
                --bg: #f3f4f6;
                --card-bg: #ffffff;
                --text: #111827;
                --muted: #6b7280;
                --surface: #ffffff;
            }
            .dark {
                --bg: #0b1220;
                --card-bg: #0f1724;
                --text: #e5e7eb;
                --muted: #9ca3af;
                --surface: #0b1220;
            }
            body { background: var(--bg); color: var(--text); }
            .card, .card-body, .table, .modal-content, header.bg-white { background: var(--card-bg); color: var(--text); }
            a, .nav-link-header { color: var(--text); }
        </style>
        <div class="min-h-screen" id="page-root">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>
        </div>

    <!-- Bootstrap JS para funcionamento dos modais -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dark mode toggle: persists preference in localStorage
        (function(){
            const body = document.getElementById('app-body');
            const storageKey = 'prefers-dark';
            function apply(mode){
                if(mode === 'dark') body.classList.add('dark'); else body.classList.remove('dark');
            }
            // init from localStorage or system preference
            const stored = localStorage.getItem(storageKey);
            if(stored) apply(stored);
            else if(window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) apply('dark');

            // expose a toggle function for the nav button
            window.toggleDarkMode = function(){
                const isDark = body.classList.toggle('dark');
                localStorage.setItem(storageKey, isDark ? 'dark' : 'light');
            }
        })();
    </script>

    <!-- Stacked scripts from views (modals, AJAX handlers, etc.) -->
    @stack('scripts')

    </body>
</html>
