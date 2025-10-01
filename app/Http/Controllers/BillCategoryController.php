<?php

namespace App\Http\Controllers;

use App\Models\BillCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BillCategoryController extends Controller
{
    // Create category
  // Create category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = auth()->user();

        if (!$user->hasRole('house_owner') && !$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $category = BillCategory::create([
            'name' => $request->name,
            'house_owner_id' => $user->id, // assign owner
        ]);

        return response()->json(['message' => 'Category created', 'data' => $category], 201);
    }

    // List categories for logged-in owner
    // List categories
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $categories = BillCategory::with('houseOwner')->get();
        } elseif ($user->hasRole('house_owner')) {
            // House Owner sees only their categories
            $categories = BillCategory::whereHas('houseOwner', function ($q) use ($user) {
                $q->where('id', $user->id);
            })->get();
        } else {
            // Tenant sees none
            $categories = collect();
        }

        return response()->json($categories);
    }

    //     public function index()
    // {
    //     $u = auth()->user();
    //     if($u->hasRole('admin')) return response()->json(BillCategory::with('houseOwner')->get());
    //     return response()->json(BillCategory::where('house_owner_id',$u->id)->get());
    // }

    public function update(Request $r,$id)
    {
        $u = Auth::user();
        $c = BillCategory::where('id',$id)->where('house_owner_id',$u->id)->firstOrFail();
        $r->validate(['name'=>'required|string|max:255']);
        $c->update(['name'=>$r->name]);
        return response()->json(['message'=>'Updated','category'=>$c]);
    }

        public function destroy($id)
    {

        $u = Auth::user();
        $c = BillCategory::where('id',$id)->where('house_owner_id',$u->id)->firstOrFail();
        $c->delete();
        return response()->json(['message'=>'Deleted']);
    }
}