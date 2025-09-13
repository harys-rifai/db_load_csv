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
        
    Schema::create('master_replikasi_status', function (Blueprint $table) {
            $table->id(); // SERIAL PRIMARY KEY
            $table->text('hostname');
            $table->text('master');
            $table->text('replika');
            $table->text('status');
            $table->timestamp('last_synch')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_replikasi_status');
    }
};
