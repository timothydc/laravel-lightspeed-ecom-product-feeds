<?php
declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductFeed
 *
 * @package TimothyDC\LightspeedEcomProductFeed\Models
 *
 * @property int $id
 * @property string $uuid
 * @property string $api_key
 * @property string $api_secret
 * @property string $cron_expression
 * @property string $language
 * @property string $base_url
 *
 * @method static \Illuminate\Database\Eloquent\Model|\TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed find(mixed $id)
 */
class ProductFeed extends Model
{

}
