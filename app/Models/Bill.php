<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Flat;
use App\Models\BillCategory;



class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'flat_id',
        'bill_category_id',
        'month',
        'amount',
        'status',
        'notes',
        'due_amount'
    ];
    // Bill belongs to flat
    public function flat(): BelongsTo
    {
        return $this->belongsTo(Flat::class);
    }

    // Bill belongs to category
    public function category(): BelongsTo
    {
        return $this->belongsTo(BillCategory::class, 'bill_category_id');
    }
}
