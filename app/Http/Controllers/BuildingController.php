<?php
namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index()
    {
        $buildings = Building::with('flats')->get();
        return response()->json($buildings);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);

        $building = Building::create([
            'name' => $data['name'],
            'address' => $data['address'] ?? null,
            'house_owner_id' => $request->user()->id,
        ]);

        return response()->json(['message'=>'Building created','data'=>$building],201);
    }

    public function show(Building $building)
    {
        return response()->json($building->load('flats'));
    }
}
