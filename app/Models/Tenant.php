<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'email',
        'contact',
        'flat_id',
        'created_by',
    ];

    // Tenant belongs to a flat
    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }

    // Admin who created the tenant
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
