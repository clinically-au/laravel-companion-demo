<?php

declare(strict_types=1);

use Clinically\Companion\Support\CompanionConfig;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(CompanionConfig::table('audit_logs'), function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('agent_id');
            $table->string('action');
            $table->string('method', 10);
            $table->string('path');
            $table->json('payload')->nullable();
            $table->unsignedSmallInteger('response_code');
            $table->string('ip');
            $table->string('user_agent')->nullable();
            $table->unsignedInteger('duration_ms');
            $table->timestamp('created_at');

            $table->foreign('agent_id')
                ->references('id')
                ->on(CompanionConfig::table('agents'))
                ->cascadeOnDelete();

            $table->index(['agent_id', 'created_at']);
            $table->index(['action', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(CompanionConfig::table('audit_logs'));
    }
};
