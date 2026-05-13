<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('is_latest', ['Yes', 'No'])->default('No')->after('is_featured');
            $table->enum('is_trending', ['Yes', 'No'])->default('No')->after('is_latest');
            $table->enum('is_flash_sale', ['Yes', 'No'])->default('No')->after('is_trending');
            $table->enum('is_best_seller', ['Yes', 'No'])->default('No')->after('is_flash_sale');
            $table->enum('is_offer', ['Yes', 'No'])->default('No')->after('is_best_seller');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_latest', 'is_trending', 'is_flash_sale', 'is_best_seller', 'is_offer']);
        });
    }
};