<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\Building;
use App\Models\User;
use App\Models\Bill;
use App\Models\Tenant;

class Flat extends Model
{
    use HasFactory;

    protected $fillable = [
        'flat_number',
        'owner_name',
        'owner_contact',
        'building_id',
        'tenant_id',
        'house_owner_id'
    ];

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    // Flat belongs to a tenant
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    // Flat has many bills
    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    // Tenant isolation global scope
    protected static function booted()
    {
        static::addGlobalScope('tenantIsolation', function (Builder $builder) {
            $user = Auth::user();
            if ($user && $user->role === 'tenant') {
                // Only allow tenant to see their own flat
                $builder->where('id', $user->flat_id);
            }
        });
    }
}
