<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Flat;


class Building extends Model
{
    use HasFactory;

    protected $fillable = ['name','address','house_owner_id'];

    public function houseOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'house_owner_id');
    }

    // Building has many flats
    public function flats(): HasMany
    {
        return $this->hasMany(Flat::class);
    }
}
