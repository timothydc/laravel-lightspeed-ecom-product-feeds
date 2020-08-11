<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Mixins;

use Closure;

class StrMixin
{
    public function xmlNode(): Closure
    {
        return static function ($string) {
            return static::snake(preg_replace('/[^A-Za-z0-9\-]/', ' ', $string));
        };
    }
}
