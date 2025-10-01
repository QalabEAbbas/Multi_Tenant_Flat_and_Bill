<?php
namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Flat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantController extends Controller
{
    // Admin creates tenant
    public function store(Request $request)
    {
        // Validate only the required fields according to document
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email',
            'contact' => 'required|string|max:20',
            'flat_id' => 'required|exists:flats,id',
        ]);

        // Create tenant
        $tenant = Tenant::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'flat_id' => $request->flat_id,
            'created_by' => Auth::id() // Admin who created the tenant
        ]);

        // Update flat.tenant_id for convenience
        $flat = Flat::find($request->flat_id);
        $flat->tenant_id = $tenant->id;
        $flat->save();

        return response()->json([
            'message' => 'Tenant created successfully',
            'tenant' => $tenant
        ], 201);
    }

    // Admin views tenants
    public function index()
    {
        $tenants = Tenant::with(['flat.building', 'creator'])->get();
        return response()->json($tenants);
    }

    public function show($id)
    {
        $tenant = Tenant::with(['flat.building', 'creator'])->findOrFail($id);
        return response()->json($tenant);
    }

    public function update(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|nullable|email|unique:tenants,email,' . $tenant->id,
            'contact' => 'sometimes|nullable|string|max:20',
            'flat_id' => 'sometimes|required|exists:flats,id',
        ]);

        $tenant->update($request->only('name', 'email', 'contact', 'flat_id'));

        if ($request->filled('flat_id')) {
            // Remove tenant from previous flat
            Flat::where('tenant_id', $tenant->id)->update(['tenant_id' => null]);
            Flat::find($request->flat_id)->update(['tenant_id' => $tenant->id]);
        }

        return response()->json(['message' => 'Tenant updated', 'tenant' => $tenant]);
    }

    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);
        Flat::where('tenant_id', $tenant->id)->update(['tenant_id' => null]);
        $tenant->delete();

        return response()->json(['message' => 'Tenant removed successfully']);
    }
}
