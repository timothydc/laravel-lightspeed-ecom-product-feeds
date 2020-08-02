<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Traits\Feed;

trait HasSpecificationInfo
{
    protected string $specificationTreeMainNode = 'specifications';
    protected string $specificationTreeChildNode = 'specification';

    protected function generateSpecificationInfo(array $lightspeedData): void
    {
        foreach ($this->getSpecifications($lightspeedData) as $specification) {
            $this->feed[$this->specificationTreeMainNode][$this->specificationTreeChildNode][] = $this->specificationFields($specification);
        }
    }

    protected function getSpecifications(array $lightspeedData): array
    {
        return $lightspeedData['specifications'];
    }

    protected function specificationFields(array $specification): array
    {
        return [
            'title' => ['_cdata' => $specification['name']],
            'value' => ['_cdata' => $specification['value']],
        ];
    }
}
