<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Feeds;

use Illuminate\Support\Carbon;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\HasCategoryTreeStructure;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\HasFilterInfo;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\HasImageInfo;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\HasSpecificationInfo;

class SooqrFeed extends Feed
{
    use HasCategoryTreeStructure,
        HasFilterInfo,
        HasImageInfo,
        HasSpecificationInfo;

    public bool $useVariantAsBaseProduct = true;

    protected function convertDate(string $date): string
    {
        return Carbon::createFromTimeString($date)->toRfc3339String();
    }

    protected function generateProductInfo(array $lightspeedData): void
    {
        $variant = $lightspeedData['variant'];

        $this->feed = [
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
            'default_image_thumb' => $lightspeedData['image']['thumb'] ?? '',
            'default_image_src' => $lightspeedData['image']['src'] ?? '',

            'url' => $this->baseUrl . $lightspeedData['url'] . '.html?id=' . $variant['id'],
            'article_code' => $variant['articleCode'],
            'ean' => $variant['ean'],
            'sku' => $variant['sku'],
            'tax' => $variant['tax'],
            'price_incl' => $variant['priceIncl'],
            'old_price_incl' => $variant['oldPriceIncl'],
            'stock_level' => $variant['stockLevel'],
        ];
    }
}
