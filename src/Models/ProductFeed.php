<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductFeed.
 *
 *
 * @property int $id
 * @property string $uuid
 * @property string $api_key
 * @property string $api_secret
 * @property string $cron_expression
 * @property string $language
 * @property string $base_url
 * @property string $mapping_class
 * @property string $last_updated_at
 * @property string $updated_at
 * @property string $created_at
 *
 * @method static \Illuminate\Database\Eloquent\Model|\TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed find(mixed $id)
 */
class ProductFeed extends Model
{
    protected $guarded = [];

    protected $dates = ['last_updated_at', 'updated_at', 'created_at'];
}
