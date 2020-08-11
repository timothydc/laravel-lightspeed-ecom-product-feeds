<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Feeds;

use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\Base\HasCategoryInfo;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\Base\HasFilterInfo;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\Base\HasImageInfo;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\Base\HasSpecificationInfo;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\Base\HasVariantInfo;

class StandardFeed extends Feed
{
    use HasCategoryInfo,
        HasFilterInfo,
        HasImageInfo,
        HasSpecificationInfo,
        HasVariantInfo;
}
