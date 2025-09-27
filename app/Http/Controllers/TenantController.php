<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TenantController extends Controller
{
    // Admin creates tenant
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'contact' => 'required|string|max:20',
            'flat_id' => 'required|exists:flats,id',
            'password' => 'required|string|min:8'
        ]);

        $tenant = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'flat_id' => $request->flat_id,
            'password' => Hash::make($request->password),
        ]);

        // Assign role
        $tenant->assignRole('tenant');

        return response()->json(['message' => 'Tenant created successfully', 'tenant' => $tenant], 201);
    }

    // Admin views tenants
    public function index()
    {
        $tenants = User::role('tenant')->with('flat')->get();
        return response()->json($tenants);
    }

    public function update(Request $request, $id)
    {
        $tenant = User::role('tenant')->findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $tenant->id,
            'contact' => 'sometimes|required|string|max:20',
            'flat_id' => 'sometimes|required|exists:flats,id',
            'password' => 'sometimes|nullable|string|min:8'
        ]);

        if ($request->has('name')) $tenant->name = $request->name;
        if ($request->has('email')) $tenant->email = $request->email;
        if ($request->has('contact')) $tenant->contact = $request->contact;
        if ($request->has('flat_id')) $tenant->flat_id = $request->flat_id;
        if ($request->has('password')) $tenant->password = Hash::make($request->password);

        $tenant->save();

        return response()->json([
            'message' => 'Tenant updated successfully',
            'tenant' => $tenant
        ]);
    }

    // Admin removes tenant
    public function destroy($id)
    {
        $tenant = User::role('tenant')->find($id);
        if (!$tenant) {
            return response()->json(['message' => 'Tenant not found or not a tenant'], 404);
        }

        $tenant->delete();
        return response()->json(['message' => 'Tenant removed successfully']);
    }

}

