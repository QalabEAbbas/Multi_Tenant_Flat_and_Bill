<?php
namespace App\Http\Controllers;

use App\Models\Flat;
use Illuminate\Http\Request;
use App\Models\Building;
use Illuminate\Support\Facades\Auth;



class FlatController extends Controller
{
public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if ($user->hasRole('admin')) {
            // Admin sees all flats
            $flats = Flat::with('building')->get();
        } elseif ($user->hasRole('house_owner')) {
            // House Owner sees only their own flats via building
            $flats = Flat::whereHas('building', function ($q) use ($user) {
                $q->where('house_owner_id', $user->id);
            })->with('building')->get();
        } else {
            // Tenant sees only their flat
            $flats = Flat::where('tenant_id', $user->id)->with('building')->get();
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
            'house_owner_id' => 'required|exists:users,id',
        ]);
        $data['house_owner_id'] = Auth::id();
        // Multi-tenant isolation check
        if ($request->user()->hasRole('house_owner')) {
            $building = \App\Models\Building::find($data['building_id']);
            if ($building->house_owner_id !== $request->user()->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }

        $flat = Flat::create($data);

        return response()->json(['message' => 'Flat created', 'data' => $flat], 201);
    }

    public function show(Flat $flat)
    {
        $user = auth()->user();

        // Multi-tenant isolation
        if ($user->hasRole(['house_owner', 'admin']) && $flat->building->house_owner_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($flat->load('building'));
    }

    public function update(Request $r,$id)
    {
        $flat = Flat::findOrFail($id);
        if(Auth::user()->hasRole(['house_owner', 'admin']) && $flat->building->house_owner_id !== Auth::id()) abort(403);
        $r->validate([
            'flat_number'=>'sometimes|required',
            'building_id'=>'sometimes|required|exists:buildings,id',
            'owner_name'=>'sometimes|nullable',
            'owner_contact'=>'sometimes|nullable'
        ]);
        if($r->has('building_id')){
            $building = Building::findOrFail($r->building_id);
            if(Auth::user()->hasRole(['house_owner', 'admin']) && $building->house_owner_id !== Auth::id()) abort(403);
        }
        $flat->update($r->only(['flat_number','building_id','owner_name','owner_contact']));
        return response()->json(['message'=>'Flat updated','flat'=>$flat]);
    }

    public function destroy($id)
    {
        $flat = Flat::findOrFail($id);
        if(Auth::user()->hasRole(['house_owner', 'admin']) && $flat->building->house_owner_id !== Auth::id()) abort(403);
        $flat->delete();
        return response()->json(['message'=>'Flat deleted']);
    }

}
