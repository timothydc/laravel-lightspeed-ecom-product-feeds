<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Traits\Feed;

/**
 * This trait will generate something like:
 *
 * <main_categories>
 *   <node><![CDATA[A main category]]></node>
 *   <node><![CDATA[Another main category]]></node>
 * </main_categories>
 *
 * <sub_categories>
 *   <node><![CDATA[My subcategory]]></node>
 *   <node><![CDATA[My main category's subcategory]]></node>
 *   <node><![CDATA[Another subcategory]]></node>
 *   <node><![CDATA[Last subcategory]]></node>
 * </sub_categories>
 *
 * <sub_sub_categories>
 *   <node><![CDATA[A more deeper category]]></node>
 * </sub_sub_categories>
 */
trait HasCategoryTreeStructureFlat
{
    use HasCategoryInfo;

    protected string $categoryTreeMainNodePrefix = 'main_';
    protected string $categoryTreeSubNodePrefix = 'sub_';

    protected function generateCategoryInfo(array $lightspeedData): void
    {
        // only get a select set of category fields
        $categories = collect($lightspeedData['categories'])
            ->filter(fn ($category) => $this->categorySkip($lightspeedData, $category) === false);

        // loop through each depth and add convert flat categories to a tree structure
        foreach ($categories->pluck('depth')->unique()->sort() as $depth) {
            $this->feed[$this->categoryNodeName($depth)][$this->categoryTreeChildNode][] = $categories
                ->where('depth', $depth)
                ->map(fn ($category) => $this->categoryFields($category))
                ->values()
                ->toArray();
        }
    }

    protected function categoryNodeName(int $depth): string
    {
        if ($depth === 1) {
            return $this->categoryTreeMainNodePrefix . $this->categoryTreeMainNode;
        }

        return str_repeat($this->categoryTreeSubNodePrefix, $depth - 1) . $this->categoryTreeMainNode;
    }

    protected function categoryFields(array $category): array
    {
        return ['_cdata' => $category['title']];
    }
}
