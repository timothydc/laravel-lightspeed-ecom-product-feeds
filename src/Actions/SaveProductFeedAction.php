<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Actions;

use Ramsey\Uuid\Uuid;
use TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed;

class SaveProductFeedAction
{
    public function execute(array $data, ProductFeed $productFeed = null): ProductFeed
    {
        if ($productFeed) {
            return $this->update($data, $productFeed);
        }

        return $this->create($data);
    }

    private function create(array $data): ProductFeed
    {
        $productFeed = new ProductFeed($data);
        $productFeed->uuid = Uuid::uuid4();
        $productFeed->save();

        return $productFeed;
    }

    private function update(array $data, ProductFeed $productFeed): ProductFeed
    {
        $productFeed->update($data);

        return $productFeed;
    }
}