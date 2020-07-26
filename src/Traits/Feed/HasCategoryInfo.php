<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Traits\Feed;

trait HasCategoryInfo
{
    protected function generateCategoryInfo(array $lightspeedData): void
    {
        foreach ($this->getCategories($lightspeedData) as $category) {
            $this->feed['categories']['category'][] = $this->categoryFields($category);
        }
    }

    protected function getCategories(array $lightspeedData): array
    {
        return $lightspeedData['categories'];
    }

    protected function categoryFields(array $category): array
    {
        return [
            'title' => ['_cdata' => $category['title']],
            'url' => ($this->baseUrl . $category['url']),
            'depth' => $category['depth'],
        ];
    }
}
