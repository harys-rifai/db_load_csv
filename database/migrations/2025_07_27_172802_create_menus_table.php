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
        
        Schema::create('menus', function (Blueprint $table) {
                    $table->id();
                    $table->string('title');
                    $table->string('icon')->nullable();
                    $table->string('route')->nullable();
                    $table->unsignedBigInteger('parent_id')->nullable()->index('menus_parent_id_index');
                    $table->integer('order')->default(0);
                    $table->timestamps();

                    // Optional: Add foreign key constraint if parent_id references the same table
                    // $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
                });
    }


     

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
