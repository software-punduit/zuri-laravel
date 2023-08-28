<?php

namespace App\Models;

use App\Models\Menu;
use App\Traits\ActivatesResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Restaurant extends Model
{
    use HasFactory;
    use ActivatesResource;

    const ACTIVE = 1;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'description',
        'user_id',
        'active'
    ];

    /**
     * Get all of the menuItems for the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function menuItems(): HasMany
    {
        return $this->hasMany(Menu::class);
    }
}

