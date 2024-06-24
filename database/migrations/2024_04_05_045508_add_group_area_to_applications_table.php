<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->unsignedBigInteger('group_area')->nullable()->after('vm_dev'); 
            $table->foreign('group_area')->references('id')->on('group_areas')->onDelete('set null');

            $table->unsignedBigInteger('product_by')->nullable()->after('group_area'); 
            $table->foreign('product_by')->references('id')->on('companies')->onDelete('set null');

            // $table->unsignedBigInteger('old_pic')->nullable()->after('vm_dev');
            // $table->foreign('old_pic')->references('id')->on('pics')->onDelete('set null');

            // $table->unsignedBigInteger('new_pic')->nullable()->after('old_pic');
            // $table->foreign('new_pic')->references('id')->on('pics')->onDelete('set null'); 

            // $table->unsignedBigInteger('backup_pic')->nullable()->after('new_pic');
            // $table->foreign('backup_pic')->references('id')->on('pics')->onDelete('set null');

            // $table->unsignedBigInteger('pic_ict')->nullable()->after('backup_pic');
            // $table->foreign('pic_ict')->references('id')->on('pics')->onDelete('set null');

            // $table->unsignedBigInteger('topology')->nullable()->after('pic_ict');
            // $table->foreign('topology')->references('id')->on('topologies')->onDelete('set null');

            // $table->unsignedBigInteger('technology')->nullable()->after('topology');
            // $table->foreign('technology')->references('id')->on('technologies')->onDelete('set null');

            // $table->unsignedBigInteger('virtual_machine')->nullable()->after('technology');
            // $table->foreign('virtual_machine')->references('id')->on('virtual_machines')->onDelete('set null');    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['group_area_id']);
            $table->dropColumn('group_area_id');
        });
    }
};
