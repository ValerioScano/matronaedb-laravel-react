<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;

abstract class Controller
{
    protected function buildTagPairs(Collection $allTags): Collection
    {
        return $allTags->groupBy('category')->map(function (Collection $categoryTags) {
            $baseTags = $categoryTags->filter(fn($t) => !str_ends_with($t->name, '?'))->sortBy('name')->values();
            $byBase   = $categoryTags->filter(fn($t) => str_ends_with($t->name, '?'))->keyBy(fn($t) => rtrim($t->name, '?'));

            return $baseTags->map(fn($tag) => [
                'base'      => $tag,
                'uncertain' => $byBase[$tag->name] ?? null,
            ]);
        });
    }
}
