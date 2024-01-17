<?php

namespace Dcodegroup\ActivityLog\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity-log:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Activity Log Feature';

    /**
     * @return void
     */
    public function handle()
    {
        if (! Schema::hasTable('activity_logs') && ! DB::table('migrations')->where('migration', 'like', '%create_activity_logs_table')->exists()) {
            $this->comment('Publishing Activity Log Migrations');
            $this->callSilent('vendor:publish', ['--tag' => 'activity-log-migrations']);
        }

        $this->comment('Publishing Activity Log Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'activity-log-config']);

        $this->comment('Publishing Activity Log Sass...');
        $this->callSilent('vendor:publish', ['--tag' => 'activity-log-sass']);

        $this->comment('Publishing Activity Log Assets..');
        $this->callSilent('vendor:publish', ['--tag' => 'activity-log-assets']);

        $this->info('Activity Log scaffolding installed successfully.');
    }
}
