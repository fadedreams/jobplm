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
        Schema::create('listing_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('listing_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('shortlisted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listing_user');
    }
};
