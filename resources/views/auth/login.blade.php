<x-guest-layout>
    <div class="auth-wrap">
        <div class="auth-card">
            <div class="auth-side">
                <div class="logo-placeholder">LP</div>
                <h2>Bem-vindo de volta</h2>
                <p>Faça login para acessar o CRUD de tarefas.</p>
                <p class="footer-note">Aplicação simples — apenas gerenciamento de tarefas.</p>
            </div>

            <div class="auth-main">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <div class="auth-header">
                    <div>
                        <h3>Entrar</h3>
                        <p class="small-note">Insira suas credenciais para continuar</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-field">
                        <label for="email">Email</label>
                        <x-text-input id="email" class="" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="form-field">
                        <label for="password">Senha</label>
                        <x-text-input id="password" class="" type="password" name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="form-actions">
                        <label class="small-note"><input type="checkbox" name="remember"> <span class="ms-2">Lembrar-me</span></label>
                        <div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="small-note ms-3">Esqueceu a senha?</a>
                            @endif
                            <button class="btn-primary ms-3">Entrar</button>
                        </div>
                    </div>
                </form>

                <p class="mt-6 small-note text-center">Não possui conta? <a href="{{ route('register') }}">Registre-se</a></p>
            </div>
        </div>
    </div>
</x-guest-layout>
