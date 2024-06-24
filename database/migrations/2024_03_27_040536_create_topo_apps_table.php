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
        Schema::create('topo_apps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id');
            $table->unsignedBigInteger('topo_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('app_id')->references('id')->on('applications')->onDelete('cascade');
            $table->foreign('topo_id')->references('id')->on('topologies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topo_apps');
    }
};
