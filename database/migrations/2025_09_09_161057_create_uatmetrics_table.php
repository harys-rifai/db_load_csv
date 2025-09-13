<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('uatmetrics', function (Blueprint $table) {
            $table->id();
            $table->timestamp('Timestamp');
            $table->string('Hostname');
            $table->ipAddress('IP_Address');
            $table->string('Database');
            $table->string('CPU');
            $table->string('Memory');
            $table->float('DiskVolGroupAvg');
            $table->float('DiskDataAvg');
            $table->string('ServerStatus');
            $table->integer('LongQueryCount');
            $table->integer('LockingCount');
            $table->string('PostgresVersion');
            $table->string('flag')->nullable();
            $table->string('state')->nullable();
            $table->timestamps();

            // Indexes using BTREE (default in most DBs like MySQL/PostgreSQL)
            $table->index('Timestamp');
            $table->index('Hostname');
            $table->index('IP_Address');
            $table->index('Database');
            $table->index('CPU');
            $table->index('Memory');
            $table->index('DiskVolGroupAvg');
            $table->index('DiskDataAvg');
            $table->index('ServerStatus');
            $table->index('LongQueryCount');
            $table->index('LockingCount');
            $table->index('PostgresVersion');
            $table->index('flag');
            $table->index('state');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uatmetrics');
    }
};

