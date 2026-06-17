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
                $result .= $edition->corpus_page ? ', p. ' . $edition->corpus_page . ', ': '';
                $result .= $edition->number_inscription ? ', n. ' . $edition->number_inscription : '';
            } 
            if ($edition->edition_type === 'corpus') {
                $result = $edition->corpus;
                $result .= $edition->volume ? ' ' . $edition->volume : '';
                $result .= $edition->number_inscription ? ', ' . $edition->number_inscription : '';
                }

            if ($edition->edition_type === 'book') {
                $result = $edition->corpus;
                // AGGIORNA I campi del libro
            }

            return [
                'text' => $result,
                'image' => $edition->edition_image ?? null,
                'link' => $edition->link ?? null,
            ];
        });
    }
}
