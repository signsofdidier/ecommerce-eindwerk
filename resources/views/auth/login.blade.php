<!-- resources/views/auth/login.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Superadmin Login</title>
</head>
<body>
<h1>Superadmin Login</h1>

@if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('superadmin.login') }}">
    @csrf

    <div>
        <label for="email">E-mail:</label>
        <input
            id="email"
            type="email"
            name="email"
            value="{{ old('email') }}"
            required
            autofocus
        >
    </div>

    <div>
        <label for="password">Wachtwoord:</label>
        <input
            id="password"
            type="password"
            name="password"
            required
        >
    </div>

    <button type="submit">Inloggen</button>
</form>
</body>
</html>
