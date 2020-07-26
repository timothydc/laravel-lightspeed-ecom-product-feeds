<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Traits\Feed;

trait HasImageInfo
{
    protected function generateImageInfo(array $lightspeedData): void
    {
        foreach ($lightspeedData['images'] as $image) {
            $this->feed['images']['image'][] = ['thumb' => $image['thumb'], 'src' => $image['src']];
        }
    }
}
