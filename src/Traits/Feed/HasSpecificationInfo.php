<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Traits\Feed;

trait HasSpecificationInfo
{
    protected function generateSpecificationInfo(array $lightspeedData): void
    {
        foreach ($lightspeedData['specifications'] as $specification) {
            $this->feed['specifications']['specification'][] = ['title' => ['_cdata' => $specification['name']], 'value' => $specification['value']];
        }
    }
}