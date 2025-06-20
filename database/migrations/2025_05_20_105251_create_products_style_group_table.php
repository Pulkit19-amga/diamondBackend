<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsStyleGroupTable extends Migration
{
    public function up()
    {
        Schema::create('products_style_group', function (Blueprint $table) {
            $table->increments('psg_id');

            // Change psg_category_id to unsignedInteger and add foreign key constraint
            $table->unsignedInteger('psg_category_id')->nullable()->index();
            $table->foreign('psg_category_id')
                  ->references('psc_id')
                  ->on('products_style_category')
                  ->onDelete('set null');

            $table->string('psg_name', 250)->nullable();
            $table->string('psg_image', 250)->nullable();
            $table->tinyInteger('psg_status')->nullable();
            $table->integer('psg_sort_order')->nullable();
            $table->text('psg_alias')->nullable();
            $table->tinyInteger('psg_display_in_front')->nullable();
            $table->dateTime('date_added')->nullable();
            $table->dateTime('date_modified')->nullable();
            $table->integer('added_by')->nullable();
            $table->integer('updated_by')->nullable();
        });
    }

    public function down()
    {
        Schema::table('products_style_group', function (Blueprint $table) {
            // Drop foreign key before dropping the column
            $table->dropForeign(['psg_category_id']);
        });

        Schema::dropIfExists('products_style_group');
    }
}
