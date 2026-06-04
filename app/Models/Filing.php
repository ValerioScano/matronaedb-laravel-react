<?php

namespace App\Models;

use App\Traits\HasBibliography;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Filing extends Model
{
    use SoftDeletes; 
    use HasBibliography;
    
    public function editions(): MorphMany {
        return $this->morphMany(Edition::class, 'editionable');
    }

    public function tags() : MorphToMany {
        return $this->morphToMany(Tag::class, 'taggable');
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
