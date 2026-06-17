<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'label', 'category'];

    public function filings()
    {
        return $this->morphedByMany(Filing::class, 'taggable');
    }

    public function proposals()
    {
        return $this->morphedByMany(Proposal::class, 'taggable');
    }
}
