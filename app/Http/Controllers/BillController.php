<?php
namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Flat;
use Illuminate\Http\Request;

class BillController extends Controller
{
    // Create a new bill
    public function store(Request $request)
    {
        $request->validate([
            'flat_id' => 'required|exists:flats,id',
            'bill_category_id' => 'required|exists:bill_categories,id',
            'month' => 'required|string',
            'amount' => 'required|numeric',
            'notes' => 'nullable|string'
        ]);

        // Ensure flat belongs to logged-in house owner
        $flat = Flat::where('id', $request->flat_id)
            ->whereHas('building', function($q) {
                $q->where('house_owner_id', auth()->id());
            })
            ->first();

        if (! $flat) {
            return response()->json(['error' => 'Unauthorized: This flat does not belong to you'], 403);
        }
        
        // --- Dues Management ---
        $previousUnpaid = Bill::where('flat_id', $request->flat_id)
            ->where('status', 'unpaid')
            ->sum('amount'); // sum of unpaid amounts

        $totalAmount = $request->amount + $previousUnpaid;

        $bill = Bill::create([
            'flat_id' => $request->flat_id,
            'bill_category_id' => $request->bill_category_id,
            'month' => $request->month,
            'amount' => $request->amount,
            'notes' => $request->notes,
            'status' => 'unpaid',
            'due_amount' => $previousUnpaid
        ]);

        return response()->json([
                'message' => 'Bill created successfully with dues',
                'bill' => $bill,
                'total_amount_due' => $totalAmount
            ], 201);
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            // Admin sees all bills
            $bills = Bill::with(['flat.building', 'category'])->get();
        } elseif ($user->hasRole('house_owner')) {
            // House Owner sees only bills for their buildings
            $bills = Bill::whereHas('flat.building', function($q) use ($user) {
                $q->where('house_owner_id', $user->id);
            })->with(['flat', 'category'])->get();
        } elseif ($user->hasRole('tenant')) {
            // Tenant sees only bills for their flat
            $bills = Bill::where('flat_id', $user->flat_id)
                        ->with(['flat', 'category'])
                        ->get();
        } else {
            $bills = collect(); // default empty
        }

        return response()->json($bills);
    }

public function pay(Request $request, $id)
{
    $bill = Bill::findOrFail($id);

    $user = $request->user();
    $flat = $bill->flat;
    $building = $flat->building;

    // Check authorization:
    if ($user->hasRole('house_owner') && $building->house_owner_id != $user->id) {
        return response()->json(['error' => 'Unauthorized: You cannot pay this bill'], 403);
    }

    if ($user->hasRole('tenant')) {
        // Ensure tenant belongs to this flat
        if ($flat->tenant_id != $user->id) { 
            return response()->json(['error' => 'Unauthorized: You cannot pay this bill'], 403);
        }
    }

    // Mark bill as paid
    $bill->status = 'paid';
    $bill->due_amount = 0;
    $bill->save();

    // --- Email Notification Logic ---
    // Example (pseudo-code):
    // Mail::to($building->house_owner->email)->send(new BillPaidNotification($bill));
    // Mail::to($user->email)->send(new BillPaidConfirmation($bill));

    return response()->json([
        'message' => 'Bill marked as paid successfully',
        'bill' => $bill
    ]);
}


}
