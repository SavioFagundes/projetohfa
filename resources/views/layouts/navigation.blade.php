<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center h-20 justify-center">
            <div class="flex flex-wrap justify-center items-center gap-6 w-full py-2">
                
                <div style="flex:1"></div>
                <div class="d-flex align-items-center">
                    {{-- Dark mode toggle --}}
                    <button type="button" onclick="window.toggleDarkMode?.()" class="dark-toggle me-2" aria-label="Alternar modo escuro" title="{{ __('Alternar modo escuro') }}">🌓</button>
                </div>
                @auth
                    @unless(request()->routeIs('tarefas.*'))
                    <form method="POST" action="{{ route('logout') }}" class="inline ms-3" id="logout-form">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:6px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 8v8"/>
                            </svg>
                            {{ __('Sair') }}
                        </button>
                    </form>
                    {{-- Visible logout link that also submits the same form and redirects to login on success --}}
                    <script>
                        document.addEventListener('DOMContentLoaded', function(){
                            const link = document.getElementById('logout-link');
                            const form = document.getElementById('logout-form');
                            if(!link || !form) return;
                            link.addEventListener('click', function(e){
                                e.preventDefault();
                                // submit form via fetch to keep on-page and then redirect to login
                                const data = new FormData(form);
                                fetch(form.action, { method: 'POST', body: data, credentials: 'same-origin' })
                                    .then(r => {
                                        // on success (204/302) redirect to login route
                                        window.location.href = '{{ route('login') }}';
                                    })
                                    .catch(() => { window.location.href = '{{ route('login') }}'; });
                            });
                        });
                    </script>
                    @endunless
                @endauth
            </div>
            
        </div>
    </div>
<style>
.nav-link-header {
    color: #374151;
    font-weight: 500;
    font-size: 15px;
    margin-right: 10px;
    text-decoration: none;
    padding: 6px 12px;
    border-radius: 6px;
    transition: background .15s, color .15s;
}
.nav-link-header:hover, .nav-link-header.active {
    background: #f3f4f6;
    color: #0d6efd;
}
.user-label {
    font-weight: 600;
    color: #0d6efd;
    font-size: 15px;
}
.user-email {
    color: #6c757d;
    font-size: 13px;
    margin-right: 8px;
}
.btn-logout {
    background: linear-gradient(90deg,#ef4444,#f59e42);
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 6px 14px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: opacity .15s;
}
.btn-logout svg { vertical-align: middle; }
.btn-logout:hover {
    opacity: .85;
}
.nav-logout {
    color: #ef4444;
    text-decoration: none;
    font-weight: 600;
}
.nav-logout:hover { text-decoration: underline; }
@media (max-width: 700px) {
    .user-label, .user-email { display: none; }
    .nav-link-header { font-size: 14px; padding: 5px 8px; }
    .flex.flex-col.items-center { align-items: flex-start !important; }
}
/* Dark toggle visual sizing */
.dark-toggle {
    font-size: 20px;
    padding: 6px 10px;
    border-radius: 8px;
    background: transparent;
    border: 1px solid rgba(0,0,0,0.06);
    cursor: pointer;
    transition: transform .12s ease, box-shadow .12s ease;
}
.dark-toggle:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.06); }
.dark-toggle:focus { outline: none; box-shadow: 0 0 0 3px rgba(13,110,253,0.12); }
.dark .dark-toggle { border-color: rgba(255,255,255,0.12); background: rgba(255,255,255,0.02); }
</style>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <!-- Mobile menu intentionally left minimal (logout only handled in header) -->
    </div>
</nav>
