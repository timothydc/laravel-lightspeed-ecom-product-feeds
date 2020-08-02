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

    protected function prepareProductInput(): void
    {
        $this->productDataInput = [
            'id' => 1234567890,
            'createdAt' => now()->toAtomString(),
            'updatedAt' => now()->addMinutes(30)->toAtomString(),
            'isVisible' => true,
            'isFeatured' => false,
            'visibility' => 'visible',
            'hits' => 0,
            'data01' => '',
            'data02' => '',
            'data03' => '',
            'url' => 'our-little-dummy-url',
            'title' => 'Dummy product',
            'fulltitle' => 'The most dummy product',
            'description' => '',
            'content' => '',
            'brand' => false,
            'supplier' => false,
            'deliverydate' => [
                'id' => 1,
                'createdAt' => now()->toAtomString(),
                'updatedAt' => now()->toAtomString(),
                'name' => 'Instant with lightspeed',
                'inStockMessage' => 'Available: instant',
                'outStockMessage' => 'Out of stock',
            ],
            'image' => [
                'id' => 1,
                'thumb' => 'https://cdn.webshopapp.com/shops/12345/files/00987654/50x50x2/file.jpg',
                'src' => 'https://cdn.webshopapp.com/shops/12345/files/00987654/file.jpg',
            ],
            'images' => [
                [
                    'id' => 12,
                    'createdAt' => now()->toAtomString(),
                    'updatedAt' => now()->toAtomString(),
                    'sortOrder' => 1,
                    'thumb' => 'https://cdn.webshopapp.com/shops/12345/files/00987654/50x50x2/file.jpg',
                    'src' => 'https://cdn.webshopapp.com/shops/12345/files/00987654/file.jpg',
                ],
            ],
            'categories' => [
                [
                    'id' => 121,
                    'createdAt' => now()->toAtomString(),
                    'updatedAt' => now()->toAtomString(),
                    'isVisible' => true,
                    'depth' => 1,
                    'sortOrder' => 1,
                    'image' => false,
                    'url' => 'cat1',
                    'title' => 'Category 1',
                ],
                [
                    'id' => 122,
                    'createdAt' => now()->toAtomString(),
                    'updatedAt' => now()->toAtomString(),
                    'isVisible' => true,
                    'depth' => 2,
                    'sortOrder' => 1,
                    'image' => false,
                    'url' => 'cat1/cat1-1',
                    'title' => 'Category 1-1',
                ],
                [
                    'id' => 123,
                    'createdAt' => now()->toAtomString(),
                    'updatedAt' => now()->toAtomString(),
                    'isVisible' => true,
                    'depth' => 3,
                    'sortOrder' => 3,
                    'image' => false,
                    'url' => 'cat1/cat1-1/cat1-1-1',
                    'title' => 'Category 1-1-1',
                ],
                [
                    'id' => 124,
                    'createdAt' => now()->toAtomString(),
                    'updatedAt' => now()->toAtomString(),
                    'isVisible' => true,
                    'depth' => 1,
                    'sortOrder' => 1,
                    'image' => false,
                    'url' => 'cat2',
                    'title' => 'Category 2',
                ],
            ],
            'variant' => [
                'id' => 1234567,
                'createdAt' => now()->toAtomString(),
                'updatedAt' => now()->toAtomString(),
                'isDefault' => true,
                'sortOrder' => 1,
                'articleCode' => 'CODE XQE',
                'ean' => '978020137962',
                'sku' => '',
                'hs' => null,
                'tax' => 0.21,
                'priceExcl' => 4.1322,
                'priceIncl' => 5,
                'priceCost' => 1.98,
                'oldPriceExcl' => 0,
                'oldPriceIncl' => 0,
                'stockTracking' => 'enabled',
                'stockLevel' => 411,
                'stockAlert' => 0,
                'stockMinimum' => 0,
                'stockSold' => 10,
                'stockBuyMininum' => 1,
                'stockBuyMinimum' => 1,
                'stockBuyMaximum' => 10000,
                'weight' => 0,
                'volume' => 0,
                'colli' => 0,
                'sizeX' => 0,
                'sizeY' => 0,
                'sizeZ' => 0,
                'matrix' => false,
                'title' => 'Default',
            ],
            'relations' => [],
            'filters' => [
                [
                    'id' => 12,
                    'createdAt' => now()->toAtomString(),
                    'updatedAt' => now()->toAtomString(),
                    'sortOrder' => 3,
                    'title' => 'Color',
                    'values' => [
                        [
                            'id' => 112,
                            'sortOrder' => 1,
                            'title' => 'Red',
                        ],
                        [
                            'id' => 113,
                            'sortOrder' => 2,
                            'title' => 'Green',
                        ],
                    ],
                ],
            ],
            'reviews' => [],
            'specifications' => [
                [
                    'id' => 4342,
                    'name' => 'Packages',
                    'value' => 5,
                ],
                [
                    'id' => 4341,
                    'name' => 'Airtight',
                    'value' => 'Yes',
                ],
                [
                    'id' => 4346,
                    'name' => 'Bonkers',
                    'value' => '',
                ],
            ],
            'discounts' => [],
        ];
    }

    protected function prepareProductOutput(): void
    {
        $this->productDataOutput = [
            'unique_id' => 1234567,
            'assoc_id' => 1234567890,
            'create_date' => now()->toAtomString(),
            'update_date' => now()->addMinutes(30)->toAtomString(),
            'is_featured' => 0,
            //            'data01' => '',
            //            'data02' => '',
            //            'data03' => '',
            'title' => ['_cdata' => 'Dummy product'],
            'fulltitle' => ['_cdata' => 'The most dummy product'],
            //            'description' => ['_cdata' => ''],
            //            'content' => ['_cdata' => ''],
            //            'brand' => ['_cdata' => ''],
            //            'supplier' => ['_cdata' => ''],
            'thumb' => 'https://cdn.webshopapp.com/shops/12345/files/00987654/50x50x2/file.jpg',
            'src' => 'https://cdn.webshopapp.com/shops/12345/files/00987654/file.jpg',
            'url' => 'http://base.url/our-little-dummy-url.html?id=1234567',
            'article_code' => 'CODE XQE',
            'ean' => '978020137962',
            //            'sku' => '',
            'tax' => 0.21,
            'price_incl' => 5,
            //            'old_price_incl' => 0,
            'stock_level' => 411,
            'images' => [
                'node' => [
                    [
                        'thumb' => 'https://cdn.webshopapp.com/shops/12345/files/00987654/50x50x2/file.jpg',
                        'src' => 'https://cdn.webshopapp.com/shops/12345/files/00987654/file.jpg',
                    ],
                ],
            ],
            'categories' => [
                'node' => [
                    [
                        'title' => ['_cdata' => 'Category 1'],
                        'sub_categories' => [
                            'node' => [
                                [
                                    'title' => ['_cdata' => 'Category 1-1'],
                                    'sub_categories' => [
                                        'node' => [
                                            [
                                                'title' => ['_cdata' => 'Category 1-1-1',],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => ['_cdata' => 'Category 2',],
                    ],
                ],
            ],
            'filters' => [
                'node' => [
                    [
                        'title' => ['_cdata' => 'Color'],
                        'values' => [
                            'node' => [
                                ['_cdata' => 'Red'],
                                ['_cdata' => 'Green'],
                            ],
                        ],
                    ],
                ],
            ],
            'specifications' => [
                'node' => [
                    [
                        'title' => ['_cdata' => 'Packages'],
                        'value' => ['_cdata' => 5],
                    ],
                    [
                        'title' => ['_cdata' => 'Airtight'],
                        'value' => ['_cdata' => 'Yes'],
                    ],
                ],
            ],
        ];
    }

    protected function prepareXmlOutput(): void
    {
        $this->productXmlOutput = '<?xml version="1.0"?>
<products>
  <product>
    <unique_id>1234567</unique_id>
    <assoc_id>1234567890</assoc_id>
    <update_date>' . now()->addMinutes(30)->toAtomString() . '</update_date>
    <create_date>' . now()->toAtomString() . '</create_date>
    <is_featured>0</is_featured>
    <title><![CDATA[Dummy product]]></title>
    <fulltitle><![CDATA[The most dummy product]]></fulltitle>
    <thumb>https://cdn.webshopapp.com/shops/12345/files/00987654/50x50x2/file.jpg</thumb>
    <src>https://cdn.webshopapp.com/shops/12345/files/00987654/file.jpg</src>
    <url>http://base.url/our-little-dummy-url.html?id=1234567</url>
    <article_code>CODE XQE</article_code>
    <ean>978020137962</ean>
    <tax>0.21</tax>
    <price_incl>5</price_incl>
    <stock_level>411</stock_level>
    <images>
      <node>
        <thumb>https://cdn.webshopapp.com/shops/12345/files/00987654/50x50x2/file.jpg</thumb>
        <src>https://cdn.webshopapp.com/shops/12345/files/00987654/file.jpg</src>
      </node>
    </images>
    <categories>
      <node>
        <title><![CDATA[Category 1]]></title>
        <sub_categories>
          <node>
            <title><![CDATA[Category 1-1]]></title>
            <sub_categories>
              <node>
                <title><![CDATA[Category 1-1-1]]></title>
              </node>
            </sub_categories>
          </node>
        </sub_categories>
      </node>
      <node>
        <title><![CDATA[Category 2]]></title>
      </node>
    </categories>
    <filters>
      <node>
        <title><![CDATA[Color]]></title>
        <values>
          <node><![CDATA[Red]]></node>
          <node><![CDATA[Green]]></node>
        </values>
      </node>
    </filters>
    <specifications>
      <node>
        <title><![CDATA[Packages]]></title>
        <value>
          <_cdata>5</_cdata>
        </value>
      </node>
      <node>
        <title><![CDATA[Airtight]]></title>
        <value><![CDATA[Yes]]></value>
      </node>
    </specifications>
  </product>
</products>
';
    }
}
