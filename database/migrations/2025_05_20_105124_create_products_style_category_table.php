<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products_style_category', function (Blueprint $table) {
            $table->increments('psc_id'); // Primary Key - unsigned integer
            $table->integer('psc_category_id')->nullable()->index();
            $table->string('psc_name', 250)->nullable();
            $table->string('psc_image', 250)->nullable();
            $table->tinyInteger('psc_status')->nullable();
            $table->integer('psc_sort_order')->nullable();
            $table->text('psc_alias')->nullable();
            $table->tinyInteger('psc_display_in_front')->nullable();
            $table->dateTime('date_added')->nullable();
            $table->dateTime('date_modified')->nullable();
            $table->integer('added_by')->nullable();
            $table->integer('updated_by')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products_style_category');
    }
};
