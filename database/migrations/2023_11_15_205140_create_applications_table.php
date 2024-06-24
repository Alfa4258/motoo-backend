<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->nullable()->index();
            $table->string('short_name')->nullable()->index();
            $table->string('long_name')->nullable()->index();
            $table->text('description')->nullable(); 
            $table->string('status')->nullable()->index();
            $table->string('image')->nullable();
            $table->string('tier')->nullable()->index();
            $table->string('category')->nullable()->index();
            $table->string('platform')->nullable()->index();
            $table->string('url_prod')->nullable();
            $table->string('url_dev')->nullable();
            $table->string('vm_prod')->nullable();
			$table->string('vm_dev')->nullable();
            $table->string('business_process')->nullable();
            $table->string('user_login')->nullable();
            $table->string('technical_doc')->nullable();
            $table->string('user_doc')->nullable();
            $table->string('other_doc')->nullable();
            $table->string('db_connection_path')->nullable();
            $table->string('sap_connection_path')->nullable();
            $table->string('ad_connection_path')->nullable();
            $table->string('information')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
