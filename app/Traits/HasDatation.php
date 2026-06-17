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
                $min = $this->min_year;
                $max = $this->max_year;
                $certainty = $this->is_certain_date ? '' : ' ~';

                if ($min === null && $max === null) {
                    return 'N/A' . $certainty;
                }

                if ($min === null) {
                    $era = $max < 0 ? 'BCE' : 'CE';
                    return 'N/A - ' . abs($max) . ' ' . $era . $certainty;
                }

                if ($max === null) {
                    $era = $min < 0 ? 'BCE' : 'CE';
                    return abs($min) . ' ' . $era . ' - N/A' . $certainty;
                }

                $minBce = $min < 0;
                $maxBce = $max < 0;

                if ($minBce === $maxBce) {
                    $era = $minBce ? 'BCE' : 'CE';
                    return abs($min) . ' - ' . abs($max) . ' ' . $era . $certainty;
                }

                return abs($min) . ' BCE - ' . $max . ' CE' . $certainty;
            }
        );
    }
}
