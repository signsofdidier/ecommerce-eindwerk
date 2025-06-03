<!-- resources/views/superadmin/tenants/create.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nieuw Tenant Aanmaken</title>
</head>
<body>
<h1>Nieuwe Tenant</h1>

@if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('superadmin.tenants.store') }}">
    @csrf

    <div>
        <label for="subdomain">Subdomain:</label>
        <input id="subdomain" name="subdomain" type="text" value="{{ old('subdomain') }}" required>
        <small>Alleen letters, cijfers en streepjes (_dash_) toegestaan.</small>
    </div>

    <div>
        <label for="name">Naam:</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" required>
    </div>

    <button type="submit">Opslaan</button>
</form>
<p><a href="{{ route('superadmin.tenants.index') }}">← Terug naar lijst</a></p>
</body>
</html>
