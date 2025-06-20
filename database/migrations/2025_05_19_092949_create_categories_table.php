<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->string('category_name');
            $table->string('category_alias')->nullable();
            $table->text('category_description')->nullable();

            $table->boolean('is_display_front')->default(false);
            $table->string('category_image')->nullable();
            $table->string('category_header_banner')->nullable();

            $table->tinyInteger('category_status')->default(1); // 1 = active, 0 = inactive
            $table->string('seo_url')->nullable();
            $table->string('category_meta_title')->nullable();
            $table->text('category_meta_description')->nullable();
            $table->text('category_meta_keyword')->nullable();
            $table->string('category_h1_tag')->nullable();

            $table->integer('sort_order')->default(0);
            $table->boolean('deleted')->default(false);

            $table->timestamp('category_date_added')->nullable();
            $table->timestamp('category_date_modified')->nullable();

            $table->unsignedBigInteger('added_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            // Foreign key constraint (optional)
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
