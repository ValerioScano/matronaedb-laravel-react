<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property string $region
 * @property string $province
 * @property string|null $city
 */
trait HasOrigin
{
    protected function location(): Attribute
    {
        return Attribute::make(
            get: fn () => implode(', ', array_filter([
                $this->region,
                $this->province,
                $this->city,
            ]))
        );
    }
}