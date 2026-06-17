<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ExternalResource extends Model
{
    protected $fillable = ['name', 'link'];

    public function external_resourceable(): MorphTo
    {
        return $this->morphTo();
    }
}
