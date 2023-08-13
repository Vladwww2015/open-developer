<?php

namespace OpenDeveloper\Developer\Console;

use Illuminate\Console\Command;

class UninstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'developer:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uninstall the developer package';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->confirm('Are you sure to uninstall open-developer?')) {
            return;
        }

        $this->removeFilesAndDirectories();

        $this->line('<info>Uninstalling open-developer!</info>');
    }

    /**
     * Remove files and directories.
     *
     * @return void
     */
    protected function removeFilesAndDirectories()
    {
        $this->laravel['files']->deleteDirectory(config('developer.directory'));
        $this->laravel['files']->deleteDirectory(public_path('vendor/open-developer/'));
        $this->laravel['files']->delete(config_path('developer.php'));
    }
}
