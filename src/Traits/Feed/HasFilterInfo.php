<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Traits\Feed;

trait HasFilterInfo
{
    protected function generateFilterInfo(array $lightspeedData): void
    {
        foreach ($lightspeedData['filters'] as $filter) {
            $productFilter = ['title' => $filter['title']];

            foreach ($filter['values'] as $filterValue) {
                $productFilter['values']['value'][] = ['title' => $filterValue['title']];
            }

            $this->feed['filters']['filter'][] = $productFilter;
        }
    }
}