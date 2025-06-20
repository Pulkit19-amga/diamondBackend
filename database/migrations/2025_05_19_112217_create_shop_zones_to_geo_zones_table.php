<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shop_zones_to_geo_zones', function (Blueprint $table) {
            $table->id('association_id'); // Primary key

            // Correct foreign key types
            $table->unsignedBigInteger('country_id')->nullable()->comment('References countries table');
            $table->unsignedBigInteger('zone_id')->nullable()->comment('References shop_zones table');
            $table->unsignedInteger('geo_zone_id')->nullable()->comment('References geo_zones table');

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            // Admin user references
            $table->unsignedBigInteger('created_by')->nullable()->comment('Admin user who created');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('Admin user who updated');

            // Indexes
            $table->index('country_id');
            $table->index('zone_id');
            $table->index('geo_zone_id');
            $table->index('created_by');
            $table->index('updated_by');

            // Foreign key constraints
            $table->foreign('country_id', 'fk_z2g_country')
                ->references('country_id')
                ->on('countries')
                ->nullOnDelete();

            $table->foreign('zone_id', 'fk_z2g_zone')
                ->references('zone_id')
                ->on('shop_zones')
                ->nullOnDelete();

            $table->foreign('geo_zone_id', 'fk_z2g_geo_zone')
                ->references('geo_zone_id')
                ->on('geo_zones')
                ->nullOnDelete();

            $table->foreign('created_by', 'fk_z2g_created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('updated_by', 'fk_z2g_updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('shop_zones_to_geo_zones', function (Blueprint $table) {
            $table->dropForeign('fk_z2g_country');
            $table->dropForeign('fk_z2g_zone');
            $table->dropForeign('fk_z2g_geo_zone');
            $table->dropForeign('fk_z2g_created_by');
            $table->dropForeign('fk_z2g_updated_by');
        });

        Schema::dropIfExists('shop_zones_to_geo_zones');
    }
};
