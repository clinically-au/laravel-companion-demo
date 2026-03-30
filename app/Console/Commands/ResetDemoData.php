<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('app:reset-demo-data')]
#[Description('Reset the demo database to a clean seeded state')]
class ResetDemoData extends Command
{
    public function handle(): int
    {
        $this->components->info('Resetting demo data...');

        DB::prohibitDestructiveCommands(false);

        $this->call('migrate:fresh', ['--force' => true, '--seed' => true]);

        DB::prohibitDestructiveCommands(app()->isProduction());

        $this->components->info('Demo data has been reset.');

        return self::SUCCESS;
    }
}
