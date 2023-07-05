<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\InteractsWithMedia;

class RestaurantTable extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    const PENCE_TO_POUND = 100;
    const MEDIA_COLLECTION = 'restaurant-tables';

    protected $fillable = [
        'restaurant_id',
        'name',
        'reservation_fee',
        'active'
    ];

    /* protected $appends = [
        'photo_url'
    ]; */

    /**
     * Get the restaurant that owns the RestaurantTable
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    protected function reservationFee(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ($value  / self::PENCE_TO_POUND),
            set: fn (string $value) => ($value * self::PENCE_TO_POUND),
        );
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_COLLECTION)
            ->singleFile()
            ->useFallbackUrl('/img/table-placeholder.jpg')
            ->useFallbackPath(asset('img/table-placeholder.jpg'));
    }

    protected function photoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFirstMediaUrl(self::MEDIA_COLLECTION),
        );
    }

}
