<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Flat;
use App\Models\BillCategory;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\BillCreatedNotification;
use App\Mail\BillPaidNotification;


class BillController extends Controller
{
  // Create bill
    public function store(Request $request)
    {
        $request->validate([
            'flat_id' => 'required|exists:flats,id',
            'bill_category_id' => 'required|exists:bill_categories,id',
            'month' => 'required|string',
            'amount' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $user = auth()->user();

        $flat = Flat::with('building')->find($request->flat_id);

        if ($user->hasRole('house_owner') && $flat->building->house_owner_id != $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Dues management
        $previousUnpaid = Bill::where('flat_id', $flat->id)
                            ->where('status', 'unpaid')
                            ->sum('amount');

        $bill = Bill::create([
            'flat_id' => $flat->id,
            'bill_category_id' => $request->bill_category_id,
            'month' => $request->month,
            'amount' => $request->amount,
            'status' => 'unpaid',
            'due_amount' => $previousUnpaid,
            'notes' => $request->notes,
        ]);
        return response()->json([
            'message' => 'Bill created successfully',
            'bill' => $bill,
            'total_amount_due' => $request->amount + $previousUnpaid
        ], 201);
    }
    // Update bill
public function update(Request $request, $id)
{
    $bill = Bill::with('flat.building')->findOrFail($id);
    $user = auth()->user();

    // Authorization check
    if ($user->hasRole('house_owner') && $bill->flat->building->house_owner_id != $user->id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    if ($user->hasRole('tenant')) {
        return response()->json(['message' => 'Tenants cannot update bills'], 403);
    }

    // Validation
    $request->validate([
        'flat_id' => 'sometimes|exists:flats,id',
        'bill_category_id' => 'sometimes|exists:bill_categories,id',
        'month' => 'sometimes|string',
        'amount' => 'sometimes|numeric',
        'status' => 'sometimes|in:unpaid,paid',
        'notes' => 'nullable|string',
    ]);

    // Update fields if provided
    if ($request->has('flat_id')) {
        $bill->flat_id = $request->flat_id;
    }
    if ($request->has('bill_category_id')) {
        $bill->bill_category_id = $request->bill_category_id;
    }
    if ($request->has('month')) {
        $bill->month = $request->month;
    }
    if ($request->has('amount')) {
        $bill->amount = $request->amount;
    }
    if ($request->has('status')) {
        $bill->status = $request->status;
        if ($request->status === 'paid') {
            $bill->due_amount = 0;
        }
    }
    if ($request->has('notes')) {
        $bill->notes = $request->notes;
    }

    $bill->save();

    return response()->json([
        'message' => 'Bill updated successfully',
        'bill' => $bill
    ]);
}

    // List bills
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $bills = Bill::with(['flat.building', 'category'])->get();
        } elseif ($user->hasRole('house_owner')) {
            $bills = Bill::whereHas('flat.building', function ($q) use ($user) {
                $q->where('house_owner_id', $user->id);
            })->with(['flat', 'category'])->get();
        } elseif ($user->hasRole('tenant')) {
            $bills = Bill::where('flat_id', $user->flat_id)
                        ->with(['flat', 'category'])
                        ->get();
        } else {
            $bills = collect();
        }

        return response()->json($bills);
    }

 // Pay bill (only Admin/House Owner)
    public function pay(Request $request, $id)
    {
        $bill = Bill::with('flat.building')->findOrFail($id);
        $user = auth()->user();

        if ($user->hasRole('house_owner') && $bill->flat->building->house_owner_id != $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($user->hasRole('tenant')) {
            return response()->json(['message' => 'Tenants cannot mark bills as paid'], 403);
        }

        $bill->status = 'paid';
        $bill->due_amount = 0;
        $bill->save();

        // TODO: Send queued email notification

        return response()->json(['message' => 'Bill marked as paid', 'bill' => $bill]);
    }

   public function show($id)
    {
        $u = auth()->user();
        $q = Bill::with(['flat.building','category']);
        if($u->hasRole('house_owner')){
            $q->whereHas('flat.building', function($x) use ($u){ $x->where('house_owner_id',$u->id); });
        }
        return response()->json($q->findOrFail($id));
    }

    public function markAsPaid(Request $request, $id)
    {
        $bill = Bill::findOrFail($id);

        $bill->status = 'paid';
        $bill->save();

        return response()->json(['message' => 'Bill marked as paid', 'bill' => $bill]);
    }

        public function destroy($id)
    {
        $u = auth()->user();
        $q = Bill::query();
        if($u->hasRole('house_owner')){
            $q->whereHas('flat.building', function($x) use ($u){ $x->where('house_owner_id',$u->id); });
        }
        $bill = $q->findOrFail($id);
        $bill->delete();
        return response()->json(['message'=>'Bill deleted']);
    }




}
