<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Traits\Feed;

use Illuminate\Support\Str;
use TimothyDC\LightspeedEcomProductFeed\Traits\Feed\Base\HasSpecificationInfo;

trait HasSpecificationsAsNodes
{
    use HasSpecificationInfo;

    protected function generateSpecificationInfo(array $lightspeedData): void
    {
        foreach ($this->getSpecifications($lightspeedData) as $specification) {
            if ($this->specificationSkip($lightspeedData, $specification)) {
                continue;
            }

            // check for reserved words
            $nodeName = $this->specificationTreeMainNode . Str::xmlNode($specification['name']);
            if (in_array(strtolower($nodeName), (property_exists($this, 'reservedWords') ? $this->reservedWords : []), true)) {
                $nodeName = 'specification_' . $nodeName;
            }

            $this->feed[$nodeName] = $this->specificationFields($specification);
        }
    }

    protected function specificationFields(array $specification): array
    {
        return [['_cdata' => (string)$specification['value']]];
    }
}
