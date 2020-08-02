<?php

use Faker\Generator;
use Ramsey\Uuid\Uuid;
use TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed;

/* @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(ProductFeed::class, static function (Generator $faker) {
    return [
        'uuid' => Uuid::uuid4(),
        'language' => 'nl',
        'cron_expression' => '30 */4 * * *',
        'base_url' => 'https://base.url/',
        'api_key' => 'my_hidden_api_key',
        'api_secret' => 'my_hidden_api_secret',
        'mapping_class' => null,
        'last_updated_at' => $faker->time(),
    ];
});
