<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Traits\Feed\Base;

trait HasVariantInfo
{
    protected string $variantTreeMainNode = 'variants';
    protected string $variantTreeChildNode = 'variant';

    protected function generateVariantInfo(array $lightspeedData): void
    {
        foreach ($this->getVariants($lightspeedData) as $variant) {

            if ($this->variantSkip($lightspeedData, $variant)) {
                continue;
            }

            $this->feed[$this->variantTreeMainNode][$this->variantTreeChildNode][] = $this->variantFields($lightspeedData, $variant);
        }
    }

    protected function getVariants(array $lightspeedData): array
    {
        return $lightspeedData['variants'];
    }

    protected function variantFields(array $lightspeedData, array $variant): array
    {
        return [
            'variant_id' => $variant['id'],
            'update_date' => $this->convertDate($variant['updatedAt']),
            'create_date' => $this->convertDate($variant['createdAt']),
            'url' => $this->baseUrl . $lightspeedData['url'] . '.html?id=' . $variant['id'],
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

    protected function variantSkip(array $lightspeedData, array $variant): bool
    {
        return false;
    }
}
