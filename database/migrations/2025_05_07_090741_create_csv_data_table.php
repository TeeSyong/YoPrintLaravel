<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCsvDataTable extends Migration
{
    public function up()
    {
        Schema::create('csv_data', function (Blueprint $table) {
            $table->string('UNIQUE_KEY')->primary();
            $table->text('PRODUCT_TITLE')->nullable();
            $table->text('PRODUCT_DESCRIPTION')->nullable();
            $table->string('STYLE#')->nullable();
            $table->string('AVAILABLE_SIZES')->nullable();
            $table->string('BRAND_LOGO_IMAGE')->nullable();
            $table->string('THUMBNAIL_IMAGE')->nullable();
            $table->string('COLOR_SWATCH_IMAGE')->nullable();
            $table->string('PRODUCT_IMAGE')->nullable();
            $table->string('SPEC_SHEET')->nullable();
            $table->string('PRICE_TEXT')->nullable();
            $table->decimal('SUGGESTED_PRICE', 8, 2)->nullable();
            $table->string('CATEGORY_NAME')->nullable();
            $table->string('SUBCATEGORY_NAME')->nullable();
            $table->string('COLOR_NAME')->nullable();
            $table->string('COLOR_SQUARE_IMAGE')->nullable();
            $table->string('COLOR_PRODUCT_IMAGE')->nullable();
            $table->string('COLOR_PRODUCT_IMAGE_THUMBNAIL')->nullable();
            $table->string('SIZE')->nullable();
            $table->integer('QTY')->nullable();
            $table->decimal('PIECE_WEIGHT', 8, 2)->nullable();
            $table->decimal('PIECE_PRICE', 8, 2)->nullable();
            $table->decimal('DOZENS_PRICE', 8, 2)->nullable();
            $table->decimal('CASE_PRICE', 8, 2)->nullable();
            $table->string('PRICE_GROUP')->nullable();
            $table->integer('CASE_SIZE')->nullable();
            $table->string('INVENTORY_KEY')->nullable();
            $table->integer('SIZE_INDEX')->nullable();
            $table->string('SANMAR_MAINFRAME_COLOR')->nullable();
            $table->string('MILL')->nullable();
            $table->string('PRODUCT_STATUS')->nullable();
            $table->string('COMPANION_STYLES')->nullable();
            $table->decimal('MSRP', 8, 2)->nullable();
            $table->string('MAP_PRICING')->nullable();
            $table->string('FRONT_MODEL_IMAGE_URL')->nullable();
            $table->string('BACK_MODEL_IMAGE')->nullable();
            $table->string('FRONT_FLAT_IMAGE')->nullable();
            $table->string('BACK_FLAT_IMAGE')->nullable();
            $table->string('PRODUCT_MEASUREMENTS')->nullable();
            $table->string('PMS_COLOR')->nullable();
            $table->string('GTIN')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('csv_data');
    }
}