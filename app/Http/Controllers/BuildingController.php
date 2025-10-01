<?php
namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
    
class BuildingController extends Controller
{
    public function index()
    {
            $user = Auth::user();

            if ($user->hasRole('admin')) {
                $buildings = Building::with('flats')->get();
            } else {
                $buildings = Building::where('house_owner_id', $user->id)->with('flats')->get();
            }

            return response()->json($buildings);
    }

    public function store(Request $r)
    {
        $r->validate([
            'name'=>'required|string|max:255',
            'address'=>'nullable|string'
        ]);

        abort_unless(Auth::user()->hasRole(['house_owner', 'admin']),403);
        $b = Building::create([
            'name'=>$r->name,
            'address'=>$r->address,
            'house_owner_id'=>Auth::id()
        ]);
        return response()->json(['message'=>'Building created','building'=>$b],201);
    }

    public function show($id)
    {
        $b = Building::with('flats')->findOrFail($id);
        // enforce owner can only view their building
        if(Auth::user()->hasRole(['house_owner', 'admin']) && $b->house_owner_id !== Auth::id()) abort(403);
        return response()->json($b);
    }
        public function update(Request $r, $id)
    {
        $b = Building::findOrFail($id);

        if (Auth::user()->hasRole(['house_owner', 'admin']) && $b->house_owner_id !== Auth::id()) {
            abort(403);
        }

        $r->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'nullable|string'
        ]);

        $b->update($r->only(['name', 'address']));

        return response()->json(['message' => 'Building updated', 'building' => $b]);
    }

      public function destroy($id)
    {
        $b = Building::findOrFail($id);

        // Only owner or admin can delete
        if (Auth::user()->hasRole(['house_owner', 'admin']) && $b->house_owner_id !== Auth::id()) {
            abort(403);
        }

        $b->delete(); // soft delete

        return response()->json(['message' => 'Building deleted']);
    }
}
