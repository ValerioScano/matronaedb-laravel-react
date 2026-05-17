<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function filings() {
        return $this->belongsToMany(Filing::class);
    }
}
