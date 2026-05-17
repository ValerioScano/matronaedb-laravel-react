<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    public function proposedBy() {
        return $this->belongsTo(User::class, 'proposed_by');
    }

    public function approvedBy() {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function filing() {
        return $this->belongsTo(Filing::class);
    }
}
