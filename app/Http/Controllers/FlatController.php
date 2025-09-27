<?php
namespace App\Http\Controllers;

use App\Models\Flat;
use Illuminate\Http\Request;

class FlatController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $flats = \App\Models\Flat::with('building')->get();
        } else {
            $flats = \App\Models\Flat::with('building')->tenantScope()->get();
        }

        return response()->json($flats);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'flat_number' => 'required|string|max:50',
            'owner_name' => 'nullable|string|max:255',
            'owner_contact' => 'nullable|string|max:100',
            'building_id' => 'required|exists:buildings,id',
        ]);

        // ensure the user owns the building (multi-tenant isolation)
        if ($request->user()->role === 'house_owner' && 
            $request->user()->id !== \App\Models\Building::find($data['building_id'])->owner_id) {
            return response()->json(['message'=>'Unauthorized'],403);
        }

        $flat = Flat::create($data);

        return response()->json(['message'=>'Flat created','data'=>$flat],201);
    }

    public function show(Flat $flat)
    {
        return response()->json($flat->load('building'));
    }
}
