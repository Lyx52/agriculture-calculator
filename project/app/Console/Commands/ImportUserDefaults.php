<?php

namespace App\Console\Commands;

use App\Jobs\ImportUserDefaultsJob;
use App\Models\User;
use App\Models\UserDefaultImports;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class ImportUserDefaults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-user-defaults {userId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ImportUserDefaultsJob::dispatch(User::findOrFail($this->argument('userId')));
    }
}
