<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Building;
use App\Models\User;
use App\Models\Bill;


class Flat extends Model
{
    use HasFactory;

    protected $fillable = [
        'flat_number',
        'owner_name',
        'owner_contact',
        'building_id',
    ];

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    // Flat belongs to a tenant
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tenant_id'); // optional if you store tenant_id
    }

    // Flat has many bills
    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }
}
