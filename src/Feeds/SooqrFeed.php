<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Feeds;

use Illuminate\Support\Carbon;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\Base\HasFilterInfo;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\Base\HasImageInfo;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\HasCategoryTreeStructureFlat;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\HasSpecificationsAsNodes;

class SooqrFeed extends Feed
{
    use HasCategoryTreeStructureFlat,
        HasFilterInfo,
        HasImageInfo,
        HasSpecificationsAsNodes;

    public bool $useVariantAsBaseProduct = true;

    public function __construct()
    {
        $this->categoryTreeChildNode = 'node';
        $this->filterTreeChildNode = 'node';
        $this->filterValueTreeChildNode = 'node';
        $this->imageTreeChildNode = 'node';
    }

    protected function convertDate(string $date): string
    {
        return Carbon::createFromTimeString($date)->toRfc3339String();
    }

    protected function generateProductInfo(array $lightspeedData): void
    {
        $variant = $lightspeedData['variant'];

        $this->feed = collect([
            'unique_id' => $variant['id'],
            'assoc_id' => $lightspeedData['id'],
            'update_date' => $this->convertDate($lightspeedData['updatedAt']),
            'create_date' => $this->convertDate($lightspeedData['createdAt']),
            'is_featured' => $lightspeedData['isFeatured'] ? 1 : 0,
            'data01' => $lightspeedData['data01'],
            'data02' => $lightspeedData['data02'],
            'data03' => $lightspeedData['data03'],
            'title' => ['_cdata' => $lightspeedData['title']],
            'fulltitle' => ['_cdata' => $lightspeedData['fulltitle']],
            'description' => ['_cdata' => $lightspeedData['description']],
            'content' => ['_cdata' => $lightspeedData['content']],
            'brand' => ['_cdata' => $lightspeedData['brand']['title'] ?? ''],
            'supplier' => ['_cdata' => $lightspeedData['supplier']['title'] ?? ''],
            'thumb' => $lightspeedData['image']['thumb'] ?? '',
            'src' => $lightspeedData['image']['src'] ?? '',
            'url' => $this->baseUrl . $lightspeedData['url'] . '.html?id=' . $variant['id'],
            'article_code' => $variant['articleCode'],
            'ean' => $variant['ean'],
            'sku' => $variant['sku'],
            'tax' => $variant['tax'],
            'price_incl' => $variant['priceIncl'],
            'old_price_incl' => $variant['oldPriceIncl'],
            'stock_level' => $variant['stockLevel'],
        ])
            ->filter(fn ($value) => (
                (is_array($value) && array_key_exists('_cdata', $value) && $value['_cdata'] !== '')
                || (is_array($value) === false && $value !== '')
            ))
            ->filter(fn ($value, $key) => $key !== 'old_price_incl' || $variant === 0)
            ->toArray();
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
