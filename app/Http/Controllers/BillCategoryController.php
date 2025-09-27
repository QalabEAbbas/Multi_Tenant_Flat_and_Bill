<?php

namespace App\Http\Controllers;

use App\Models\BillCategory;
use Illuminate\Http\Request;

class BillCategoryController extends Controller
{
    // Create category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = BillCategory::create([
            'house_owner_id' => auth()->id(), // logged-in owner
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Bill Category created successfully', 'category' => $category]);
    }

    // List categories for logged-in owner
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            // Admin sees all categories
            $categories = BillCategory::with('owner')->get();
        } elseif ($user->hasRole('house_owner')) {
            // House Owner sees only their own categories
            $categories = BillCategory::where('house_owner_id', $user->id)->get();
        } else {
            $categories = collect(); // Tenant or other roles see none
        }

        return response()->json($categories);
    }
}