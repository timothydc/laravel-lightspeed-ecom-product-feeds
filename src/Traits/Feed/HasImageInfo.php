<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Traits\Feed;

trait HasImageInfo
{
    protected function generateImageInfo(array $lightspeedData): void
    {
        foreach ($this->getImages($lightspeedData) as $image) {
            $this->feed['images']['image'][] = $this->imageFields($image);
        }
    }

    protected function getImages(array $lightspeedData): array
    {
        return $lightspeedData['images'];
    }

    protected function imageFields(array $image): array
    {
        return ['thumb' => $image['thumb'], 'src' => $image['src']];
    }
}
