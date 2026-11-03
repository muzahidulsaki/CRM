<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetLeadsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leads:reset {--force : Force execution without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Safely delete all leads, follow-ups and reset lead IDs to start from the beginning (PS10001)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (!$this->option('force')) {
            if (!$this->confirm('Are you sure you want to delete ALL leads and reset the ID sequence? This cannot be undone.')) {
                $this->info('Operation cancelled.');
                return Command::SUCCESS;
            }
        }

        $this->warn('Resetting leads table and related follow-ups...');

        Schema::disableForeignKeyConstraints();

        DB::table('lead_follow_ups')->truncate();
        DB::table('leads')->truncate();

        // Also clean orphaned references in quotations and orders if any
        DB::table('quotations')->whereNotNull('lead_id')->update(['lead_id' => null]);
        DB::table('orders')->whereNotNull('lead_id')->update(['lead_id' => null]);

        Schema::enableForeignKeyConstraints();

        $this->info('All leads have been deleted and auto-increment has been reset to 1.');
        $this->info('The next lead created will start fresh from the first ID (PS10001).');

        return Command::SUCCESS;
    }
}

