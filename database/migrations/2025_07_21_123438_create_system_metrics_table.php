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
        Schema::create('system_metrics', function (Blueprint $table) {
            $table->timestamp('timestamp');
            $table->text('hostname');
            $table->text('environment');
            $table->text('cpu_usage');
            $table->text('memory_usage');
            $table->text('disk_usage');
            $table->text('network_usage');
            $table->text('status');
            $table->text('extra1');
            $table->text('extra2');
            $table->text('file_name');
            $table->text('load_status');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_metrics');
    }
};
