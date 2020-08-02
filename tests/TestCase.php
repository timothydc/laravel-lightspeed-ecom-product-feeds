<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Tests;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use TimothyDC\LightspeedEcomProductFeed\LightspeedEcomProductFeedServiceProvider;

class TestCase extends Orchestra
{
    protected array $productDataInput = [];
    protected array $productDataOutput = [];
    protected string $productBaseUrl = 'http://base.url/';
    protected string $productXmlOutput = '';

    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow();
        $this->prepareProductInput();
        $this->prepareProductOutput();
        $this->prepareXmlOutput();

        $this->withFactories(__DIR__ . '/database/factories');
    }

    protected function getPackageProviders($app): array
    {
        return [
            LightspeedEcomProductFeedServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        Schema::dropAllTables();

        include_once __DIR__ . '/../database/migrations/create_product_feed_table.php.stub';
        (new \CreateProductFeedTable())->up();
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
            'variants' =>
                [
                    [
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
            'product_id' => 1234567890,
            'create_date' => now()->toDateTimeString(),
            'update_date' => now()->addMinutes(30)->toDateTimeString(),
            'is_featured' => 0,
            'data01' => '',
            'data02' => '',
            'data03' => '',
            'title' => ['_cdata' => 'Dummy product'],
            'fulltitle' => ['_cdata' => 'The most dummy product'],
            'description' => ['_cdata' => ''],
            'content' => ['_cdata' => ''],
            'brand' => ['_cdata' => ''],
            'supplier' => ['_cdata' => ''],
            'default_image_thumb' => 'https://cdn.webshopapp.com/shops/12345/files/00987654/50x50x2/file.jpg',
            'default_image_src' => 'https://cdn.webshopapp.com/shops/12345/files/00987654/file.jpg',
            'variants' => [
                'variant' => [
                    [
                        'url' => 'http://base.url/our-little-dummy-url.html?id=1234567',
                        'article_code' => 'CODE XQE',
                        'ean' => '978020137962',
                        'sku' => '',
                        'tax' => 0.21,
                        'price_incl' => 5,
                        'old_price_incl' => 0,
                        'stock_level' => 411,
                        'variant_id' => 1234567,
                        'update_date' => now()->toDateTimeString(),
                        'create_date' => now()->toDateTimeString(),
                        'sort_order' => 1,
                        'title' => 'Default',
                        'price_excl' => 4.1322,
                        'old_price_excl' => 0,
                        'stock_tracking' => 'enabled',
                        'stock_sold' => 10,
                        'stock_buy_minimum' => 1,
                        'stock_buy_maximum' => 10000,
                    ]
                ]
            ],
            'images' => [
                'image' => [
                    [
                        'thumb' => 'https://cdn.webshopapp.com/shops/12345/files/00987654/50x50x2/file.jpg',
                        'src' => 'https://cdn.webshopapp.com/shops/12345/files/00987654/file.jpg',
                    ],
                ],
            ],
            'categories' => [
                'category' => [
                    [
                        'title' => ['_cdata' => 'Category 1'],
                        'url' => 'http://base.url/cat1',
                        'depth' => 1,
                    ],
                    [
                        'title' => ['_cdata' => 'Category 1-1'],
                        'url' => 'http://base.url/cat1/cat1-1',
                        'depth' => 2,
                    ],
                    [
                        'title' => ['_cdata' => 'Category 1-1-1',],
                        'url' => 'http://base.url/cat1/cat1-1/cat1-1-1',
                        'depth' => 3,
                    ],
                    [
                        'title' => ['_cdata' => 'Category 2',],
                        'url' => 'http://base.url/cat2',
                        'depth' => 1,
                    ],
                ],
            ],
            'filters' => [
                'filter' => [
                    [
                        'title' => ['_cdata' => 'Color'],
                        'values' => [
                            'value' => [
                                ['_cdata' => 'Red'],
                                ['_cdata' => 'Green'],
                            ],
                        ],
                    ],
                ],
            ],
            'specifications' => [
                'specification' => [
                    [
                        'title' => ['_cdata' => 'Packages'],
                        'value' => ['_cdata' => 5],
                    ],
                    [
                        'title' => ['_cdata' => 'Airtight'],
                        'value' => ['_cdata' => 'Yes'],
                    ],
                    [
                        'title' => ['_cdata' => 'Bonkers'],
                        'value' => ['_cdata' => ''],
                    ],
                ],
            ],
            'hits' => 0,
            'url' => 'http://base.url/our-little-dummy-url.html',
        ];
    }

    protected function prepareXmlOutput(): void
    {
        $this->productXmlOutput = '<?xml version="1.0"?>
<products>
  <product>
    <product_id>1234567890</product_id>
    <update_date>' . now()->addMinutes(30)->toDateTimeString() . '</update_date>
    <create_date>' . now()->toDateTimeString() . '</create_date>
    <is_featured>0</is_featured>
    <hits>0</hits>
    <data01></data01>
    <data02></data02>
    <data03></data03>
    <url>http://base.url/our-little-dummy-url.html</url>
    <title><![CDATA[Dummy product]]></title>
    <fulltitle><![CDATA[The most dummy product]]></fulltitle>
    <description><![CDATA[]]></description>
    <content><![CDATA[]]></content>
    <brand><![CDATA[]]></brand>
    <supplier><![CDATA[]]></supplier>
    <default_image_thumb>https://cdn.webshopapp.com/shops/12345/files/00987654/50x50x2/file.jpg</default_image_thumb>
    <default_image_src>https://cdn.webshopapp.com/shops/12345/files/00987654/file.jpg</default_image_src>
    <images>
      <image>
        <thumb>https://cdn.webshopapp.com/shops/12345/files/00987654/50x50x2/file.jpg</thumb>
        <src>https://cdn.webshopapp.com/shops/12345/files/00987654/file.jpg</src>
      </image>
    </images>
    <categories>
      <category>
        <title><![CDATA[Category 1]]></title>
        <url>http://base.url/cat1</url>
        <depth>1</depth>
      </category>
      <category>
        <title><![CDATA[Category 1-1]]></title>
        <url>http://base.url/cat1/cat1-1</url>
        <depth>2</depth>
      </category>
      <category>
        <title><![CDATA[Category 1-1-1]]></title>
        <url>http://base.url/cat1/cat1-1/cat1-1-1</url>
        <depth>3</depth>
      </category>
      <category>
        <title><![CDATA[Category 2]]></title>
        <url>http://base.url/cat2</url>
        <depth>1</depth>
      </category>
    </categories>
    <variants>
      <variant>
        <variant_id>1234567</variant_id>
        <update_date>' . now()->toDateTimeString() . '</update_date>
        <create_date>' . now()->toDateTimeString() . '</create_date>
        <url>http://base.url/our-little-dummy-url.html?id=1234567</url>
        <sort_order>1</sort_order>
        <title>Default</title>
        <article_code>CODE XQE</article_code>
        <ean>978020137962</ean>
        <sku></sku>
        <tax>0.21</tax>
        <price_excl>4.1322</price_excl>
        <price_incl>5</price_incl>
        <old_price_excl>0</old_price_excl>
        <old_price_incl>0</old_price_incl>
        <stock_tracking>enabled</stock_tracking>
        <stock_level>411</stock_level>
        <stock_sold>10</stock_sold>
        <stock_buy_minimum>1</stock_buy_minimum>
        <stock_buy_maximum>10000</stock_buy_maximum>
      </variant>
    </variants>
    <filters>
      <filter>
        <title><![CDATA[Color]]></title>
        <values>
          <value><![CDATA[Red]]></value>
          <value><![CDATA[Green]]></value>
        </values>
      </filter>
    </filters>
    <specifications>
      <specification>
        <title><![CDATA[Packages]]></title>
        <value>
          <_cdata>5</_cdata>
        </value>
      </specification>
      <specification>
        <title><![CDATA[Airtight]]></title>
        <value><![CDATA[Yes]]></value>
      </specification>
      <specification>
        <title><![CDATA[Bonkers]]></title>
        <value><![CDATA[]]></value>
      </specification>
    </specifications>
  </product>
</products>
';
    }
}
