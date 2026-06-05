<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property int $min_year
 * @property int $max_year
 * @property bool $is_certain_date
 */
trait HasDatation
{
    protected function datation(): Attribute
    {
        return Attribute::make(
            get: function () {
                $format = fn ($year) => $year < 0
                    ? abs($year) . ' BCE'
                    : $year . ' CE';

                $certainty = $this->is_certain_date ? '' : ' ~';

                return $format($this->min_year) . ' - ' . $format($this->max_year) . $certainty;
            }
        );
    }
}
