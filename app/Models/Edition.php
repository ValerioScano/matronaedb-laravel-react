<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Edition extends Model
{
    public function filing() {
        return $this->belongsTo(Filing::class);
    }
}
