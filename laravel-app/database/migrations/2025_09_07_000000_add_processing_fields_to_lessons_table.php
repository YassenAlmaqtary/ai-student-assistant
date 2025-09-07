<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            if (!Schema::hasColumn('lessons', 'progress')) {
                $table->unsignedTinyInteger('progress')->default(0)->after('status');
            }
            if (!Schema::hasColumn('lessons', 'processing_started_at')) {
                $table->timestamp('processing_started_at')->nullable()->after('progress');
            }
            if (!Schema::hasColumn('lessons', 'processing_finished_at')) {
                $table->timestamp('processing_finished_at')->nullable()->after('processing_started_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            if (Schema::hasColumn('lessons', 'progress')) {
                $table->dropColumn('progress');
            }
            if (Schema::hasColumn('lessons', 'processing_started_at')) {
                $table->dropColumn('processing_started_at');
            }
            if (Schema::hasColumn('lessons', 'processing_finished_at')) {
                $table->dropColumn('processing_finished_at');
            }
        });
    }
};
