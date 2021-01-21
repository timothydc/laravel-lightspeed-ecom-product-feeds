<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Feeds;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\Base\HasImageInfo;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\HasCategoryTreeStructureFlat;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\HasFiltersAsNodes;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\HasSpecificationsAsNodes;

class SooqrFeed extends Feed
{
    use HasCategoryTreeStructureFlat,
        HasFiltersAsNodes,
        HasImageInfo,
        HasSpecificationsAsNodes;

    public bool $useVariantAsBaseProduct = true;

    public function __construct()
    {
        $this->specificationTreeMainNode = '';
        $this->filterTreeChildNode = '';
        $this->filterValueTreeChildNode = 'node';
        $this->categoryTreeChildNode = 'node';
        $this->imageTreeChildNode = 'node';
    }

    protected function convertDate(string $date): string
    {
        return Carbon::createFromTimeString($date)->toRfc3339String();
    }

    protected function generateProductInfo(array $lightspeedData): void
    {
        parent::generateProductInfo($lightspeedData);

        $this->feed = collect($this->feed)
            ->filter(fn ($value) => (
                (is_array($value) && array_key_exists('_cdata', $value) && $value['_cdata'] !== '')
                || (is_array($value) === false && $value !== '')
            ))
            ->filter(fn ($value, $key) => $key !== 'old_price_incl'
                || ($key === 'old_price_incl' && $value > 0))
            ->toArray();

        $this->feed['unique_id'] = $this->feed['variant_id'];
        $this->feed['assoc_id'] = $this->feed['product_id'];
        $this->feed['src'] = $this->feed['default_image_src'];
        $this->feed['thumb'] = Str::replaceFirst('50x50', '150x150', $this->feed['default_image_thumb']);

        unset($this->feed['hits'],
            $this->feed['variant_id'],
            $this->feed['product_id'],
            $this->feed['default_image_src'],
            $this->feed['default_image_thumb']);
    }

    protected function specificationSkip(array $lightspeedData, array $specification): bool
    {
        return $specification['value'] === '';
    }

    protected function imageFields(array $image): array
    {
        return [$image['thumb']];
    }
}
