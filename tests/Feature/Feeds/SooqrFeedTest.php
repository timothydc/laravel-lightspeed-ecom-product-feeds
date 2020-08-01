<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Tests\Feature\Feeds;

use Spatie\ArrayToXml\ArrayToXml;
use TimothyDC\LightspeedEcomProductFeed\Feeds\SooqrFeed;
use TimothyDC\LightspeedEcomProductFeed\Tests\TestCase;

class SooqrFeedTest extends TestCase
{
    public function test_it_generates_empty_feed(): void
    {
        $sooqr = new SooqrFeed();
        $feed = $sooqr->execute($this->productBaseUrl, ['isVisible' => false]);

        self::assertEquals([], $feed);
    }

    public function test_it_generates_feed(): void
    {
        $sooqr = new SooqrFeed();
        $feed = $sooqr->execute($this->productBaseUrl, $this->productDataInput);

        self::assertEquals($this->productDataOutput, $feed);

        $arrayToXml = new ArrayToXml(['product' => [$feed]], 'products');
        $arrayToXml->setDomProperties(['formatOutput' => true]);

        self::assertEquals($this->productXmlOutput, $arrayToXml->toXml());
    }
}
