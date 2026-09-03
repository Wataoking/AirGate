<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="{{ asset('style/nike.css') }}">
</head>
<body class="chaussure">
    <section class="chaussette">
        <div class="page">
            <h1>Create Account</h1>
            <p>join us today-it's quick and easy</p>
        </div>

        @if ($errors->any())
            <div class="form-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="football">
            @csrf

            <div class="joeur">
                <label for="name"><h3>Full name</h3></label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Entrer votre nom entier" required>
            </div>

            <div class="gardien">
                <label for="email"><h3>Email</h3></label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="Entrer votre e-mail" required>
            </div>

            <div class="maillot">
                <label for="phone_number"><h3>Phone number</h3></label>
                <input id="phone_number" name="phone_number" type="text" value="{{ old('phone_number') }}" placeholder="Entrer votre numero de telephone" required>
            </div>

            <div class="but">
                <label for="password"><h3>Password</h3></label>
                <input id="password" name="password" type="password" placeholder="Entrer votre mot de passe" required>
            </div>

            <div class="ballon">
                <label for="password_confirmation"><h3>Confirm Password</h3></label>
                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Reconfirmer votre mot de passe" required>
            </div>

            <div class="gang">
                <button type="submit">Create Account</button>
            </div>

            <div class="arbitre">
                <p>Already have an account ?</p>
                <a href="{{ route('login') }}">Login</a>
            </div>

            <div class="numero">
                <p>By creating an account you agree to our</p>
                <a href="#">Terms & privacy policy</a>
            </div>
        </form>
    </section>
</body>
</html>