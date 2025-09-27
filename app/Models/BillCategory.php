<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Bill;


class BillCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'house_owner_id',
        'name',
    ];

    // Category belongs to house owner
    public function houseOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'house_owner_id');
    }

    // Category has many bills
    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

}
