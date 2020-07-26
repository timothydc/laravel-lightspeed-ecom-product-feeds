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

    protected function getCategories(array $categories): array
    {
        return $categories;
    }

    protected function categoryFields(array $category): array
    {
        return [
            'title' => $category['title'],
            'url' => ($this->baseUrl . $category['url']),
            'depth' => $category['depth'],
        ];
    }
}