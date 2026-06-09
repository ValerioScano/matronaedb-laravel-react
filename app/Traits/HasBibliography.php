<?php 

namespace App\Traits;

/**
 * @property \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection $editions
 */
trait HasBibliography {
    public function formatBibliography(): \Illuminate\Support\Collection
    {
        return $this->editions->map(function ($edition) {
            if ($edition->edition_tyoe === 'journal') {
                $result = $edition->corpus;
                $result .= ' (' . $edition->publication_year . ')';
                $result .= $edition->corpus_page ? ', p. ' . $edition->corpus_page : '';
                $result .= $edition->number_inscription ? ', n. ' . $edition->number_inscription : '';
            } else {
                $result = $edition->corpus;
                $result .= $edition->volume ? ' ' . $edition->volume : '';
                $result .= $edition->number_inscription ? ', ' . $edition->number_inscription : '';
                $result .= $edition->last_name_author ? ', ' . $edition->last_name_author : '';
            }
            return [
                'text' => $result,
                'image' => $edition->edition_image ?? null,
                'link' => $edition->link ?? null,
            ];
        });
    }
}
