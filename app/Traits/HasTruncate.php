<?php

namespace App\Traits;

/**
 * @property string $text
 */
trait HasTruncate
{
    public function textTruncate(int $length = 100, string $ellipsis = '...'): string
    {
        $text = $this->text;

        if (strlen($text) <= $length) {
            return $text;
        }

        $truncated = substr($text, 0, $length);
        $lastSpace = strrpos($truncated, ' ');

        if ($lastSpace !== false) {
            $truncated = substr($truncated, 0, $lastSpace);
        }

        return $truncated.$ellipsis;
    }
}
