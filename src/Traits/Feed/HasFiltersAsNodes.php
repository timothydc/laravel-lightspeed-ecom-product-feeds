<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Traits\Feed;

use Illuminate\Support\Str;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\Base\HasFilterInfo;

trait HasFiltersAsNodes
{
    use HasFilterInfo;

    protected function generateFilterInfo(array $lightspeedData): void
    {
        foreach ($lightspeedData['filters'] as $filter) {
            if ($this->filterSkip($lightspeedData, $filter)) {
                continue;
            }

            // check for reserved words
            $nodeName = $this->filterTreeChildNode . Str::xmlNode($filter['title']);
            if (in_array(strtolower($nodeName), (property_exists($this, 'reservedWords') ? $this->reservedWords : []), true)) {
                $nodeName = 'filter_' . $nodeName;
            }

            foreach ($filter['values'] as $filterValue) {
                if ($this->filterValueSkip($lightspeedData, $filter, $filterValue)) {
                    continue;
                }

                $this->feed[$nodeName][$this->filterValueTreeChildNode][] = ['_cdata' => $filterValue['title']];
            }
        }
    }
}
