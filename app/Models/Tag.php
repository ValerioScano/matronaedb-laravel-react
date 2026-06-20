<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'label', 'category'];

    public function isUncertain(): bool
    {
        return str_ends_with($this->name, '?');
    }

    /**
     * Inline badge color: hue is derived from the category name so every
     * category gets a distinct, stable color. Uncertain tags (name ending
     * in "?") keep the same hue but get desaturated/lightened so they read
     * as less vivid than their certain counterpart.
     */
    public function badgeStyle(): string
    {
        $hue = crc32($this->category) % 360;

        if ($this->isUncertain()) {
            return "background-color: hsl({$hue}, 35%, 80%); color: #3a3a3a;";
        }

        return "background-color: hsl({$hue}, 65%, 45%); color: #fff;";
    }

    public function filings()
    {
        return $this->morphedByMany(Filing::class, 'taggable');
    }

    public function proposals()
    {
        return $this->morphedByMany(Proposal::class, 'taggable');
    }
}
