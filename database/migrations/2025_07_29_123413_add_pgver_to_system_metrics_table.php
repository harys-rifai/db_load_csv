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
    Schema::table('system_metrics', function (Blueprint $table) {
        $table->text('pgver')->nullable()->after('extra2');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down()
        {
            Schema::table('system_metrics', function (Blueprint $table) {
                $table->dropColumn('pgver');
            });
        }

};
