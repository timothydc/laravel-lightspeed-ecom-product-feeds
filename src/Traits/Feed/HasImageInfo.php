<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Traits\Feed;

trait HasImageInfo
{
    protected string $imageTreeMainNode = 'images';
    protected string $imageTreeChildNode = 'image';

    protected function generateImageInfo(array $lightspeedData): void
    {
        foreach ($this->getImages($lightspeedData) as $image) {

            if ($this->imageSkip($lightspeedData, $image)) {
                continue;
            }

            $this->feed[$this->imageTreeMainNode][$this->imageTreeChildNode][] = $this->imageFields($image);
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

    protected function imageSkip(array $lightspeedData, array $image): bool
    {
        return false;
    }
}
