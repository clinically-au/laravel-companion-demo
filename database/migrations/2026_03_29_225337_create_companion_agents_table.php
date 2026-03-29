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
        Schema::create(CompanionConfig::table('agents'), function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('token_hash')->unique();
            $table->string('token_prefix', 16);
            $table->json('scopes');
            $table->json('ip_allowlist')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->string('last_ip')->nullable();
            $table->string('last_user_agent')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('revoked_at')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(CompanionConfig::table('agents'));
    }
};
