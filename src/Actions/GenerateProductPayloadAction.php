<?php
declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Actions;

use Illuminate\Support\Carbon;
use TimothyDC\LightspeedEcomProductFeed\Interfaces\ProductPayloadMappingInterface;

class GenerateProductPayloadAction implements ProductPayloadMappingInterface
{
    public function execute(string $baseUrl, array $product): array
    {
        if ($product['isVisible'] === false) {
            return [];
        }

        $mappedProduct = [
            'product_id' => $product['id'],
            'update_date' => Carbon::createFromTimeString($product['updatedAt'])->toDateTimeString(),
            'create_date' => Carbon::createFromTimeString($product['createdAt'])->toDateTimeString(),
            'is_featured' => $product['isFeatured'],
            'hits' => $product['hits'],
            'data01' => $product['data01'],
            'data02' => $product['data02'],
            'data03' => $product['data03'],
            'url' => $baseUrl . $product['url'] . '.html',
            'title' => ['_cdata' => $product['title']],
            'fulltitle' => ['_cdata' => $product['fulltitle']],
            'description' => ['_cdata' => $product['description']],
            'content' => ['_cdata' => $product['content']],
            'brand' => $product['brand']['title'] ?? '',
            'supplier' => $product['supplier']['title'] ?? '',
            'default_image_thumb' => $product['image']['thumb'] ?? '',
            'default_image_src' => $product['image']['src'] ?? '',
        ];

        foreach ($product['images'] as $image) {
            $mappedProduct['images']['image'][] = ['thumb' => $image['thumb'], 'src' => $image['src']];
        }

        foreach ($product['categories'] as $category) {
            $mappedProduct['categories']['category'][] = ['title' => $category['title'], 'url' => ($baseUrl . $category['url']), 'visible' => $category['isVisible']];
        }

        foreach ($product['variants'] as $variant) {
            $mappedProduct['variants']['variant'][] = [
                'variant_id' => $variant['id'],
                'update_date' => Carbon::createFromTimeString($variant['updatedAt'])->toDateTimeString(),
                'create_date' => Carbon::createFromTimeString($variant['createdAt'])->toDateTimeString(),
                'url' => $baseUrl . $product['url'] . '.html?id=' . $variant['id'],
                'sort_order' => $variant['sortOrder'],
                'title' => $variant['title'],
                'article_code' => $variant['articleCode'],
                'ean' => $variant['ean'],
                'sku' => $variant['sku'],
                'tax' => $variant['tax'],
                'price_excl' => $variant['priceExcl'],
                'price_incl' => $variant['priceIncl'],
                'old_price_excl' => $variant['oldPriceExcl'],
                'old_price_incl' => $variant['oldPriceIncl'],
                'stock_tracking' => $variant['stockTracking'],
                'stock_level' => $variant['stockLevel'],
                'stock_sold' => $variant['stockSold'],
                'stock_buy_minimum' => $variant['stockBuyMinimum'],
                'stock_buy_maximum' => $variant['stockBuyMaximum'],
            ];
        }

        foreach ($product['filters'] as $filter) {
            $productFilter = ['title' => $filter['title']];

            foreach ($filter['values'] as $filterValue) {
                $productFilter['values']['value'][] = ['title' => $filterValue['title']];
            }

            $mappedProduct['filters']['filter'][] = $productFilter;
        }

        return $mappedProduct;
    }
}
