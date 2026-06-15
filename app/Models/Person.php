<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use SoftDeletes;

    protected $fillable = ['praenomen', 'nomen', 'cognomen', 'TM_PER_id'];

    public function peopleable(): MorphTo
    {
        return $this->morphTo();
    }
}
