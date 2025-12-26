<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PingDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:ping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ping the database to keep the connection alive (Aiven Anti-Pause)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Pinging database...');
            
            // Simple lightweight query
            DB::select('SELECT 1');
            
            $this->info('Database pong! Connection is active.');
            Log::info('Scheduled Task: Database ping successful.');
            
        } catch (\Exception $e) {
            $this->error('Database ping failed: ' . $e->getMessage());
            Log::error('Scheduled Task: Database ping failed. ' . $e->getMessage());
            return 1;
        }
    }
}
