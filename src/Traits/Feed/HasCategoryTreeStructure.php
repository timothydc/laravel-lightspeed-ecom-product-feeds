<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Traits\Feed;

use Illuminate\Support\Str;

trait HasCategoryTreeStructure
{
    use HasCategoryInfo;

    protected function generateCategoryInfo(array $lightspeedData): void
    {
        // only get a select set of category fields
        $categories = collect($lightspeedData['categories'])->map(fn ($category) => $this->categoryFields($category));

        // loop through each depth and add convert flat categories to a tree structure
        foreach ($categories->pluck('depth')->unique()->sortDesc() as $depth) {
            $children = $categories->where('depth', $depth);
            $parents = $categories->where('depth', $depth - 1);

            // loop through parents and add its children
            foreach ($parents as $index => $parent) {
                $parent['sub_categories']['category'][] = $children
                    ->filter(fn ($category) => Str::contains($category['url'], $parent['url']))
                    ->toArray();

                // update parent data
                $categories->put($index, $parent);
            }
        }

        foreach ($categories->where('depth', 1) as $category) {
            $this->feed['categories']['category'][] = $category;
        }
    }
}
