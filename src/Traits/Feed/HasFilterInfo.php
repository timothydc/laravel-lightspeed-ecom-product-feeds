<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Traits\Feed;

trait HasFilterInfo
{
    protected string $filterTreeMainNode = 'filters';
    protected string $filterTreeChildNode = 'filter';

    protected string $filterValueTreeMainNode = 'values';
    protected string $filterValueTreeChildNode = 'value';

    protected function generateFilterInfo(array $lightspeedData): void
    {
        foreach ($lightspeedData['filters'] as $filter) {
            if ($this->filterSkip($lightspeedData, $filter)) {
                continue;
            }

            $productFilter = ['title' => ['_cdata' => $filter['title']]];

            foreach ($filter['values'] as $filterValue) {
                if ($this->filterValueSkip($lightspeedData, $filter, $filterValue)) {
                    continue;
                }

                $productFilter[$this->filterValueTreeMainNode][$this->filterValueTreeChildNode][] = ['_cdata' => $filterValue['title']];
            }

            $this->feed[$this->filterTreeMainNode][$this->filterTreeChildNode][] = $productFilter;
        }
    }

    protected function filterSkip(array $lightspeedData, array $filter): bool
    {
        return false;
    }

    protected function filterValueSkip(array $lightspeedData, array $filter, array $filterValue): bool
    {
        return false;
    }
}
