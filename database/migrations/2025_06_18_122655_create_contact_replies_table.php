<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contact_replies', function (Blueprint $table) {
            $table->id(); // bigint unsigned NOT NULL AUTO_INCREMENT
            $table->unsignedBigInteger('contact_us_id');
            $table->unsignedBigInteger('responded_by')->nullable();
            $table->text('message');
            $table->timestamps(); // created_at and updated_at (nullable by default)

            $table->foreign('contact_us_id')
            ->references('id')
            ->on('contact_us');


        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_replies');
    }
};
