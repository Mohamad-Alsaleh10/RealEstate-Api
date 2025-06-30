<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
        $table->string('title');
        $table->longText('description');
        $table->decimal('price', 15, 2);
        $table->string('currency')->default('SYP');
        $table->string('location');
        $table->enum('type', ['for_sale', 'for_rent']); // للبيع أو للإيجار
        $table->string('status')->default('pending'); // حالة العقار: قيد الانتظار، موافق عليه
        $table->string('latitude')->nullable();
        $table->string('longitude')->nullable();
        $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // من أضاف العقار (يمكن أن يكون مسؤول)
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
