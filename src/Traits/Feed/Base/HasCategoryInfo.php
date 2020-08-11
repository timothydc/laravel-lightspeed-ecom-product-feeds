<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Traits\Feed\Base;

/**
 * Generate
 */
trait HasCategoryInfo
{
    protected string $categoryTreeMainNode = 'categories';
    protected string $categoryTreeChildNode = 'category';

    protected function generateCategoryInfo(array $lightspeedData): void
    {
        foreach ($this->getCategories($lightspeedData) as $category) {

            if ($this->categorySkip($lightspeedData, $category)) {
                continue;
            }

            $this->feed[$this->categoryTreeMainNode][$this->categoryTreeChildNode][] = $this->categoryFields($category);
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

    protected function categorySkip(array $lightspeedData, array $category): bool
    {
        return false;
    }
}
