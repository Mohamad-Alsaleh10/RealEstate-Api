<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->decimal('average_rating', 2, 1)->nullable()->after('status'); // مثال: 4.5
            $table->integer('ratings_count')->default(0)->after('average_rating'); // عدد التقييمات
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['average_rating', 'ratings_count']);
        });
    }
};
