<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;



class Tag extends Model
{

    public function filings()
    {
        return $this->morphedByMany(Filing::class, 'taggable');
    }

    public function proposals()
    {
        return $this->morphedByMany(Proposal::class, 'taggable');
    }
}
