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
     // Tenant isolation global scope
    protected static function booted()
    {
        static::addGlobalScope('tenantIsolation', function ($builder) {
            $user = auth()->user();
            if ($user && $user->role === 'tenant') {
                $builder->whereHas('houseOwner.buildings.flats', function ($query) use ($user) {
                    $query->where('id', $user->flat_id);
                });
            }
        });
    }


}
