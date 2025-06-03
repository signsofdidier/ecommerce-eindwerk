<!-- resources/views/superadmin/tenants/edit.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tenant Bewerken</title>
</head>
<body>
<h1>Tenant Bewerken: {{ $tenant->name }}</h1>

@if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('superadmin.tenants.update', $tenant) }}">
    @csrf
    @method('PUT')

    <div>
        <label for="subdomain">Subdomain:</label>
        <input id="subdomain" name="subdomain" type="text" value="{{ old('subdomain', $tenant->subdomain) }}" required>
        <small>Alleen letters, cijfers en streepjes (_dash_) toegestaan.</small>
    </div>

    <div>
        <label for="name">Naam:</label>
        <input id="name" name="name" type="text" value="{{ old('name', $tenant->name) }}" required>
    </div>

    <button type="submit">Bijwerken</button>
</form>
<p><a href="{{ route('superadmin.tenants.index') }}">← Terug naar lijst</a></p>
</body>
</html>
