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

    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow();
        $this->prepareProductInput();
        $this->prepareProductOutput();

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

    protected function prepareProductOutput(): void
    {
        $this->productDataOutput = [
            'unique_id' => 1234567,
            'assoc_id' => 1234567890,
            'create_date' => now()->toAtomString(),
            'update_date' => now()->addMinutes(30)->toAtomString(),
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
            'url' => 'http://base.url/our-little-dummy-url.html?id=1234567',
            'article_code' => 'CODE XQE',
            'ean' => '978020137962',
            'sku' => '',
            'tax' => 0.21,
            'price_incl' => 5,
            'old_price_incl' => 0,
            'stock_level' => 411,
            'images' => [
                'image' => [
                    [
                        'thumb' => 'https://cdn.webshopapp.com/shops/12345/files/00987654/50x50x2/file.jpg',
                        'src' => 'https://cdn.webshopapp.com/shops/12345/files/00987654/file.jpg',
                    ]
                ]
            ],
            'categories' => [
                'category' => [
                    [
                        'title' => ['_cdata' => 'Category 1'],
                        'url' => 'http://base.url/cat1',
                        'depth' => 1,
                        'sub_categories' => [
                            'category' => [
                                [
                                    'title' => ['_cdata' => 'Category 1-1'],
                                    'url' => 'http://base.url/cat1/cat1-1',
                                    'depth' => 2,
                                    'sub_categories' => [
                                        'category' => [
                                            [
                                                'title' => ['_cdata' => 'Category 1-1-1',],
                                                'url' => 'http://base.url/cat1/cat1-1/cat1-1-1',
                                                'depth' => 3,
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'title' => ['_cdata' => 'Category 2',],
                        'url' => 'http://base.url/cat2',
                        'depth' => 1,
                        'sub_categories' => [
                            'category' => [
                            ],
                        ],
                    ],
                ],
            ],
        ];
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
                'hs' => NULL,
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
            'filters' => [],
            'reviews' => [],
            'specifications' => [],
            'discounts' => [],
        ];
    }
}
