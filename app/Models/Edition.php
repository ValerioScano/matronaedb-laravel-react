<?php

namespace App\Models;

use App\Traits\HasBibliography;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Edition extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'corpus',
        'volume',
        'number_inscription',
        'edition_type',
        'publication_year',
        'corpus_page',
        'last_name_author',
        'edition_image',
        'link',
    ];

    public function editionable()
    {
        return $this->morphTo();
    }
}
