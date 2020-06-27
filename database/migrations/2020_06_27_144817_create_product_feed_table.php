<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductFeedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_feeds', static function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid');
            $table->string('language');
            $table->string('cron_expression');
            $table->string('base_url');

            $table->string('api_key');
            $table->string('api_secret');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_feeds');
    }
}
