# Generate Lightspeed eCom Product feeds for Laravel

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

## Installation

Via Composer

``` bash
$ composer require timothydc/laravel-lightspeed-ecom-product-feeds
```

## Installation
You can publish all resources, or you may choose to publish them separately:

```bash
$ php artisan vendor:publish --tag="lightspeed-ecom-product-feed"

$ php artisan vendor:publish --tag="lightspeed-ecom-product-feed:config"
$ php artisan vendor:publish --tag="lightspeed-ecom-product-feed:migrations"
```

Run your migrations to create the `product_feeds` table.

```bash
$ php artisan migrate
```

You can choose to automatically run the created feeds via the task scheduler.
It is also possible to generate the feeds directly or via a queue job. See `config/lightspeed-ecom-product-feed.php` for more configuration options.

```bash
return [
    'feed_disk' => env('LS_PRODUCT_FEEDS_FEED_DISK', 'public'),

    'scheduled_tasks' => [
        'auto_run' => env('LS_PRODUCT_FEEDS_AUTO_RUN', true),
        'use_queue' => env('LS_PRODUCT_FEEDS_USE_QUEUE', true),
        'queue' => env('LS_PRODUCT_FEEDS_QUEUE', 'default')
    ],
];
```

## Available commands

Create a new product feed and answer the presented questions. Enter a valid [cron expression][link-crontab] when asked.
```bash
$ php artisan ecom-feed:create

> Enter your webshop API key:
> Enter your webshop API secret:
> Enter your cron interval:
> What language should your feed be in?
```

Show a list of all the currently created product feeds
```bash
$ php artisan ecom-feed:list
```

Remove a product feed.
```bash
$ php artisan ecom-feed:remove {product-feed-id}
```

Generate an XML by providing a product feed ID
```bash
$ php artisan ecom-feed:generate-xml {product-feed-id}
```

## Credits

- [Timothy De Cort][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[link-crontab]: https://crontab.guru/
[ico-version]: https://img.shields.io/packagist/v/timothydc/laravel-lightspeed-ecom-product-feed.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/timothydc/laravel-lightspeed-ecom-product-feed.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/timothydc/laravel-lightspeed-ecom-product-feed/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/timothydc/laravel-lightspeed-ecom-product-feed
[link-downloads]: https://packagist.org/packages/timothydc/laravel-lightspeed-ecom-product-feed
[link-travis]: https://travis-ci.org/timothydc/laravel-lightspeed-ecom-product-feed
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/timothydc
[link-contributors]: ../../contributors
