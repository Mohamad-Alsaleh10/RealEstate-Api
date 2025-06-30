<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->unsignedTinyInteger('stars'); // عدد النجوم (1-5)
            $table->text('comment')->nullable(); // تعليق اختياري
            $table->timestamps();

            // التأكد من أن المستخدم يمكنه تقييم العقار مرة واحدة فقط
            $table->unique(['user_id', 'property_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
