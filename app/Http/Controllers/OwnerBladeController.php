<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class OwnerBladeController extends Controller
{
    public function index() {
        $owners = User::where('role', 'house_owner')->get();
        return view('owners.index', compact('owners'));
    }
}
