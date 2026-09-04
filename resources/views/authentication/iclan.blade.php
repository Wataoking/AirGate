<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="{{ asset('style/nike.css') }}">
</head>
<body>
    <header class="roue">
        <div class="salade">
            <img src="{{ asset('image/logo.png') }}" alt="Logo de la plateforme">
        </div>
        <div class="car">
            <h1>Bonjour, bienvenue</h1>
        </div>

        <div class="r1">
            <div class="r2">
                <div class="meuf">
                    <p><strong>Connexion</strong> à votre compte</p>
                </div>

                @if (session('status'))
                    <p class="form-status">{{ session('status') }}</p>
                @endif

                <form class="voiture" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="moteur">
                        <label for="email">Adresse email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               placeholder="Entrer votre adresse email" required autofocus autocomplete="username">
                        @error('email')
                            <p>{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="moteur">
                        <label for="password">Mot de passe</label>
                        <input id="password" type="password" name="password"
                               placeholder="Entrer votre mot de passe" required autocomplete="current-password">
                        @error('password')
                            <p>{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="remember" for="remember">
                        <input id="remember" type="checkbox" name="remember">
                        <span>Se souvenir de moi</span>
                    </label>

                    <div class="velo">
                        <div class="cout">
                            <div class="moto">
                                <a href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                            </div>
                            <div class="neuf">
                                <button type="submit">Se connecter</button>
                            </div>
                        </div>

                        <div class="masse">
                            <a href="{{ route('register') }}">Créer un compte</a>
                        </div>
                       <a href="souscription">Souscrire a un forfait</a>
                    </div>
                </form>
            </div>
        </div>
    </header>
</body>
</html>