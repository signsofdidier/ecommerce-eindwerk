<!-- resources/views/superadmin/tenants/index.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Superadmin Dashboard &mdash; Tenants</title>
</head>
<body>
<h1>Tenants</h1>

@if (session('success'))
    <div style="color: green;">
        {{ session('success') }}
    </div>
@endif

<p><a href="{{ route('superadmin.tenants.create') }}">Nieuwe Tenant aanmaken</a></p>

<table border="1" cellpadding="5">
    <thead>
    <tr>
        <th>ID</th>
        <th>Subdomain</th>
        <th>Naam</th>
        <th>Aangemaakt op</th>
        <th>Acties</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($tenants as $tenant)
        <tr>
            <td>{{ $tenant->id }}</td>
            <td>{{ $tenant->subdomain }}</td>
            <td>{{ $tenant->name }}</td>
            <td>{{ $tenant->created_at->format('Y-m-d') }}</td>
            <td>
                <a href="{{ route('superadmin.tenants.edit', $tenant) }}">Bewerken</a> |
                <form action="{{ route('superadmin.tenants.destroy', $tenant) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Weet je het zeker?')">Verwijderen</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<!-- Paginatie links -->
{{ $tenants->links() }}

<!-- Logout voor superadmin -->
<form method="POST" action="{{ route('superadmin.logout') }}">
    @csrf
    <button type="submit">Uitloggen</button>
</form>
</body>
</html>
