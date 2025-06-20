<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id(); // bigint UNSIGNED NOT NULL AUTO_INCREMENT
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('diamond_id')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->tinyInteger('diamond_type')->default(1)->comment('1 = Natural, 2 = CVD');
            $table->decimal('price', 10, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('total_price', 12, 2);
            $table->timestamps(); // created_at and updated_at (nullable by default)

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
