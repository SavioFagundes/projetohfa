<x-guest-layout>
    <div class="auth-wrap">
        <div class="auth-card">
            <div class="auth-side">
                <div class="logo-placeholder">LP</div>
                <h2>Crie sua conta</h2>
                <p>Registre-se para começar a gerenciar suas tarefas.</p>
                <p class="footer-note">Somente um CRUD de tarefas — simples e direto.</p>
            </div>

            <div class="auth-main">
                <div class="auth-header">
                    <div>
                        <h3>Registrar</h3>
                        <p class="small-note">Preencha os dados abaixo</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-field">
                        <label for="name">Nome</label>
                        <x-text-input id="name" class="" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="form-field">
                        <label for="email">Email</label>
                        <x-text-input id="email" class="" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="form-field">
                        <label for="password">Senha</label>
                        <x-text-input id="password" class="" type="password" name="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="form-field">
                        <label for="password_confirmation">Confirmar Senha</label>
                        <x-text-input id="password_confirmation" class="" type="password" name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="form-actions">
                        <!-- Force client-side navigation to /login to avoid server-side redirect for authenticated users -->
                        <a href="/login" class="btn-ghost" onclick="event.preventDefault(); window.location.href='/login'">Já possui conta?</a>
                        <button class="btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
