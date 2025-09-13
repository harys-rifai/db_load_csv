<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah tipe kolom
        Schema::table('system_metrics', function (Blueprint $table) {
            $table->string('hostname')->change();
            $table->string('environment')->change();
            $table->string('status')->change();
            $table->string('extra1')->change();
            $table->string('extra2')->change();
            $table->string('file_name')->change();
            $table->string('load_status')->change();

            // Tambahkan kolom pgver jika belum ada
            if (!Schema::hasColumn('system_metrics', 'pgver')) {
                $table->string('pgver')->nullable()->after('load_status');
            }
        });

        // Hapus index jika sudah ada (untuk mencegah duplikasi)
        $indexesToDrop = [
            'system_metrics_timestamp_index',
            'system_metrics_hostname_index',
            'system_metrics_environment_index',
            'system_metrics_file_name_index',
            'system_metrics_pgver_index',
        ];

        foreach ($indexesToDrop as $indexName) {
            DB::statement("DROP INDEX IF EXISTS {$indexName}");
        }

        // Buat index baru
        DB::statement("CREATE INDEX system_metrics_timestamp_index ON system_metrics (timestamp)");
        DB::statement("CREATE INDEX system_metrics_hostname_index ON system_metrics (hostname)");
        DB::statement("CREATE INDEX system_metrics_environment_index ON system_metrics (environment)");
        DB::statement("CREATE INDEX system_metrics_file_name_index ON system_metrics (file_name)");
        DB::statement("CREATE INDEX system_metrics_pgver_index ON system_metrics (pgver)");
    }

    public function down(): void
    {
        // Kembalikan tipe kolom numeric ke TEXT
        DB::statement("ALTER TABLE system_metrics ALTER COLUMN cpu_usage TYPE TEXT USING cpu_usage::TEXT;");
        DB::statement("ALTER TABLE system_metrics ALTER COLUMN memory_usage TYPE TEXT USING memory_usage::TEXT;");
        DB::statement("ALTER TABLE system_metrics ALTER COLUMN disk_usage TYPE TEXT USING disk_usage::TEXT;");
        DB::statement("ALTER TABLE system_metrics ALTER COLUMN network_usage TYPE TEXT USING network_usage::TEXT;");

        // Ubah kembali tipe kolom string ke TEXT
        Schema::table('system_metrics', function (Blueprint $table) {
            $table->text('hostname')->change();
            $table->text('environment')->change();
            $table->text('status')->change();
            $table->text('extra1')->change();
            $table->text('extra2')->change();
            $table->text('file_name')->change();
            $table->text('load_status')->change();

            // Hapus kolom pgver
            $table->dropColumn('pgver');
        });

        // Hapus index
        $indexesToDrop = [
            'system_metrics_timestamp_index',
            'system_metrics_hostname_index',
            'system_metrics_environment_index',
            'system_metrics_file_name_index',
            'system_metrics_pgver_index',
        ];

        foreach ($indexesToDrop as $indexName) {
            DB::statement("DROP INDEX IF EXISTS {$indexName}");
        }
    }
};
