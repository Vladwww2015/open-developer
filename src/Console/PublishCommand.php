<?php

namespace OpenDeveloper\Developer\Console;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'developer:publish {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "publish open-developer's assets, configuration, language and migration files. If you want overwrite the existing files, you can add the `--force` option";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $force = $this->option('force');
        $options = ['--provider' => 'OpenDeveloper\Developer\DeveloperServiceProvider'];
        if ($force == true) {
            $options['--force'] = true;
        }
        $this->call('vendor:publish', $options);
        $this->call('view:clear');
    }
}
