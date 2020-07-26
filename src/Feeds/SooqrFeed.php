<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Feeds;

use Illuminate\Support\Carbon;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\HasCategoryTreeStructure;

class SooqrFeed extends StandardFeed
{
    use HasCategoryTreeStructure;

    protected function convertDate(string $date): string
    {
        return Carbon::createFromTimeString($date)->toRfc3339String();
    }

    protected function variantFields(array $lightspeedData, array $variant): array
    {
        return [
            'variant_id' => $variant['id'],
            'url' => $this->baseUrl . $lightspeedData['url'] . '.html?id=' . $variant['id'],
            'sort_order' => $variant['sortOrder'],
            'title' => $variant['title'],
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
