<?php

namespace App\Models;

use App\Models\User;
use App\Models\Constants;
use App\Models\Restaurant;
use App\Traits\ActivatesResource;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use ActivatesResource;

    const MEDIA_COLLECTION = 'menu-items';

    const ACTIVE = 1;


    protected $fillable = [
        'restaurant_id',
        'name',
        'price',
        'active',
        'restaurant_owner_id'
    ];

    protected $appends = [
        'photo_url'
    ];

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ($value  / Constants::PENCE_TO_POUND),
            set: fn (string $value) => ($value * Constants::PENCE_TO_POUND),
        );
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_COLLECTION)
            ->singleFile()
            ->useFallbackUrl('/img/menu-placeholder.jpg')
            ->useFallbackPath(asset('img/menu-placeholder.jpg'));
    }

    protected function photoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFirstMediaUrl(self::MEDIA_COLLECTION),
        );
    }

    /**
     * Get the restaurantOwner that owns the Menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurantOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'restaurant_owner_id');
    }

    /**
     * Get the restaurant that owns the Menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    // public function scopeActive($query){
    //     return $query->where('active', SELF::ACTIVE);
        
    // }
}
