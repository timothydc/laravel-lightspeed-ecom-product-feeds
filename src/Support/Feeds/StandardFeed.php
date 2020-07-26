<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Support\Feeds;

use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\HasCategoryInfo;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\HasFilterInfo;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\HasImageInfo;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\HasSpecificationInfo;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\HasVariantInfo;

class StandardFeed extends Feed
{
    use HasCategoryInfo,
        HasFilterInfo,
        HasImageInfo,
        HasSpecificationInfo,
        HasVariantInfo;
}
