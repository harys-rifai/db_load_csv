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
               
        
        Schema::create('server_cloud', function (Blueprint $table) {
            $table->id(); // NO
            $table->string('name')->index(); // Add index
            $table->string('appref')->nullable()->index(); // Add index
            $table->string('ip')->nullable()->index(); // Add index
            $table->string('environment')->nullable();
            $table->text('description')->nullable();
            $table->string('pic')->nullable();
            $table->string('tribe')->nullable();
            $table->string('version')->nullable();
            $table->string('database_name')->nullable();
            $table->string('processor')->nullable();
            $table->string('memory')->nullable();
            $table->string('storage')->nullable();
            $table->boolean('encryption')->default(false);
            $table->boolean('pii')->default(false); // PII Yes_No
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_cloud');
    }
};
