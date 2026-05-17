<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Filing extends Model
{
    use SoftDeletes; 
    
    public function editions() {
        return $this->hasMany(Edition::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }

    public function proposedBy() {
        return $this->belongsTo(User::class, 'proposed_by');
    }

    public function approvedBy() {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function proposals() {
        return $this->hasMany(Proposal::class);
    }
}
