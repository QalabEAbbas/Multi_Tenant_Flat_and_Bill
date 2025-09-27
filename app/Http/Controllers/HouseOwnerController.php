<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HouseOwnerController extends Controller
{
    /**
     * List all House Owners
     */
    public function index()
    {
        $owners = User::where('role', 'house_owner')->get();

        return response()->json([
            'status' => 'success',
            'data' => $owners
        ]);
    }

    /**
     * Create a new House Owner
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        $owner = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'house_owner',
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'House Owner created successfully',
            'data'    => $owner
        ]);
    }

    /**
     * Show House Owner details
     */
    public function show($id)
    {
        $owner = User::where('role', 'house_owner')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $owner
        ]);
    }

    /**
     * Update House Owner details
     */
    public function update(Request $request, $id)
    {
        $owner = User::where('role', 'house_owner')->findOrFail($id);

        $owner->update($request->only(['name', 'email']));

        return response()->json([
            'status'  => 'success',
            'message' => 'House Owner updated successfully',
            'data'    => $owner
        ]);
    }

    /**
     * Delete a House Owner
     */
    public function destroy($id)
    {
        $owner = User::where('role', 'house_owner')->findOrFail($id);
        $owner->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'House Owner deleted successfully'
        ]);
    }
}
