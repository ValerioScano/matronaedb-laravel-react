<?php 

namespace App\Traits;

/**
 * @property \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection $editions
 */
trait HasBibliography {
    public function formatBibliography(): \Illuminate\Support\Collection
    {
        return $this->editions->map(function ($edition) {
        $result = '';    
        if ($edition->edition_type === 'journal') {
                $result = $edition->last_name_author ? $edition->last_name_author . ', ': '';
                $result .= $edition->publication_year ? ' (' . $edition->publication_year . '), ' : '';
                $result .= $edition->corpus;
                $result .= $edition->volume ? $edition->volume : '';
                $result .= $edition->corpus_page ? ', p. ' . $edition->corpus_page : '';
                $result .= $edition->number_inscription ? ', n. ' . $edition->number_inscription : '';
            } 
            if ($edition->edition_type === 'corpus') {
                $result = $edition->corpus;
                $result .= $edition->volume ? ' ' . $edition->volume : '';
                $result .= $edition->number_inscription ? ', ' . $edition->number_inscription : '';
                }

            if ($edition->edition_type === 'book') {
                $segments = array_filter([
                    $edition->last_name_author,
                    $edition->corpus,
                    trim($edition->publication_place . ' ' . $edition->publication_year) ?: null,
                    $edition->corpus_page ? 'p. ' . $edition->corpus_page : null,
                    $edition->number_inscription ? 'nr. ' . $edition->number_inscription : null,
                ], fn ($segment) => filled($segment));
                $result = implode(', ', $segments);
            }

            return [
                'text' => $result,
                'image' => $edition->edition_image ?? null,
                'link' => $edition->link ?? null,
            ];
        });
    }
}
