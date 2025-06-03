<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    /**
     * Display a listing of the tenants (superadmin dashboard).
     */
    public function index()
    {
        // Haal alle tenants uit de database (eventueel met paginatie)
        $tenants = Tenant::orderBy('id')->paginate(10);

        // Toon een view, b.v. resources/views/superadmin/tenants/index.blade.php
        return view('superadmin.tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new tenant.
     */
    public function create()
    {
        return view('superadmin.tenants.create');
    }

    /**
     * Store a newly created tenant in storage.
     */
    public function store(Request $request)
    {
        // Valideer subdomain en naam
        $data = $request->validate([
            'subdomain' => [
                'required',
                'string',
                'alpha_dash',
                'max:50',
                'unique:tenants,subdomain',
            ],
            'name'      => ['required', 'string', 'max:255'],
        ]);

        // Maak het tenant-record aan
        $tenant = Tenant::create($data);

        // Optioneel: maak direct een admin-user voor deze tenant
        // (mocht je dat willen, anders kun je dit uit commentaar halen)
        /*
        \App\Models\User::create([
            'tenant_id'     => $tenant->id,
            'name'          => 'Admin ' . $tenant->name,
            'email'         => 'beheer@' . $tenant->subdomain . '.localhost',
            'password'      => bcrypt('password'),
            'is_super_admin'=> false,
        ]);
        */

        return redirect()->route('superadmin.tenants.index')
            ->with('success', 'Tenant succesvol aangemaakt.');
    }

    /**
     * Show the form for editing the specified tenant.
     */
    public function edit(Tenant $tenant)
    {
        return view('superadmin.tenants.edit', compact('tenant'));
    }

    /**
     * Update the specified tenant in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $data = $request->validate([
            'subdomain' => [
                'required',
                'string',
                'alpha_dash',
                'max:50',
                Rule::unique('tenants')->ignore($tenant->id),
            ],
            'name'      => ['required', 'string', 'max:255'],
        ]);

        $tenant->update($data);

        return redirect()->route('superadmin.tenants.index')
            ->with('success', 'Tenant gegevens bijgewerkt.');
    }

    /**
     * Remove the specified tenant from storage.
     */
    public function destroy(Tenant $tenant)
    {
        $tenant->delete();

        return redirect()->route('superadmin.tenants.index')
            ->with('success', 'Tenant verwijderd.');
    }

    // Je kunt method show() weglaten (resource->except('show')) of invullen als je wilt
}
