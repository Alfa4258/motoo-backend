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
        Schema::create('pic_apps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id');
            $table->unsignedBigInteger('pic_id');
            $table->string('pic_type')->nullable();

            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('app_id')->references('id')->on('applications')->onDelete('cascade');
            $table->foreign('pic_id')->references('id')->on('pics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pic_apps');
    }
};
