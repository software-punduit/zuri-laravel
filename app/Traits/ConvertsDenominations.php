<?php
namespace App\Traits;


use App\Models\Constants;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait ConvertsDenominations
{
    // const ACTIVE = 1;
    // const INACTIVE = 0;

    
    function convertDenomination() : Attribute {
        return Attribute::make(
            get: fn (string $value) => ($value  / Constants::PENCE_TO_POUND),
            set: fn (string $value) => ($value * Constants::PENCE_TO_POUND),
        );
        
    }   
}
