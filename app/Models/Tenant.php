<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'name', 'email', 'contact', 'house_owner_id'
    ];

    public function houseOwner()
    {
        return $this->belongsTo(User::class, 'house_owner_id');
    }
}
