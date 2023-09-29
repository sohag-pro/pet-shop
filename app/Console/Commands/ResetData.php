<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all data in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('migrate:refresh --seed');
    }
}
