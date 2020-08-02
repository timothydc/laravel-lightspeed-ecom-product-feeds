<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Traits\Feed;

use Illuminate\Support\Str;

trait HasCategoryTreeStructure
{
    use HasCategoryInfo;

    protected string $categoryTreeSubNode = 'sub_categories';

    protected function generateCategoryInfo(array $lightspeedData): void
    {
        // only get a select set of category fields
        $categories = collect($lightspeedData['categories']);

        // loop through each depth and add convert flat categories to a tree structure
        foreach ($categories->pluck('depth')->unique()->sortDesc() as $depth) {
            $children = $categories->where('depth', $depth);
            $parents = $categories->where('depth', $depth - 1);

            // loop through parents and add its children
            foreach ($parents as $index => $parent) {
                $parent[$this->categoryTreeSubNode][$this->categoryTreeChildNode] = $children
                    ->filter(fn ($category) => Str::contains($category['url'], $parent['url']))
                    ->map(fn ($category) => $this->categoryFields($category))
                    ->values()
                    ->toArray();

                // update parent data
                $categories->put($index, $parent);
            }
        }

        foreach ($categories->where('depth', 1) as $category) {
            $this->feed[$this->categoryTreeMainNode][$this->categoryTreeChildNode][] = $this->categoryFields($category);
        }
    }

    protected function categoryFields(array $category): array
    {
        $categoryFields = [
            'title' => ['_cdata' => $category['title']],
            'url' => ($this->baseUrl . $category['url']),
            'depth' => $category['depth'],
        ];

        if (array_key_exists($this->categoryTreeSubNode, $category)) {
            $categoryFields[$this->categoryTreeSubNode] = $category[$this->categoryTreeSubNode];
        }

        return $categoryFields;
    }
}
