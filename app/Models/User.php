<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Flat;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $guard_name = 'web'; // or whatever guard you want to use
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // we'll add this column via migration below
        // 'contact'
        // 'flat_id',
        // 'building_id' // optional, add later after Buildings migration if you prefer
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
 public function flat(): BelongsTo
    {
        return $this->belongsTo(Flat::class);
    }

    // House Owner has many buildings
    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class, 'house_owner_id');
    }

    // House Owner has many flats through buildings
    public function flats(): HasManyThrough
    {
        return $this->hasManyThrough(
            Flat::class,
            Building::class,
            'house_owner_id', // Foreign key on Building table
            'building_id',    // Foreign key on Flat table
            // 'id',             // Local key on User table
            // 'id'              // Local key on Building table
        );
    }

    // House Owner has many bill categories
    public function billCategories(): HasMany
    {
        return $this->hasMany(BillCategory::class, 'house_owner_id');
    }
}
