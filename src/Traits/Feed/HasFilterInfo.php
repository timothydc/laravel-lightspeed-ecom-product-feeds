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
            $productFilter = ['title' => ['_cdata' => $filter['title']]];

            foreach ($filter['values'] as $filterValue) {
                $productFilter[$this->filterValueTreeMainNode][$this->filterValueTreeChildNode][] = ['_cdata' => $filterValue['title']];
            }

            $this->feed[$this->filterTreeMainNode][$this->filterTreeChildNode][] = $productFilter;
        }
    }
}
