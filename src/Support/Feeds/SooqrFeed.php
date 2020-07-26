<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Support\Feeds;

use Illuminate\Support\Carbon;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\HasCategoryTreeStructure;

class SooqrFeed extends StandardFeed
{
    use HasCategoryTreeStructure;

    protected function convertDate(string $date): string
    {
        return Carbon::createFromTimeString($date)->toRfc3339String();
    }
}
