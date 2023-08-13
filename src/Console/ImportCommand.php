<?php

namespace OpenDeveloper\Developer\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use OpenDeveloper\Developer\Developer;

class ImportCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'developer:import {extension?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a Open-developer extension';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $extension = $this->argument('extension');

        if (empty($extension) || !Arr::has(Developer::$extensions, $extension)) {
            $extension = $this->choice('Please choose a extension to import', array_keys(Developer::$extensions));
        }

        $className = Arr::get(Developer::$extensions, $extension);

        if (!class_exists($className) || !method_exists($className, 'import')) {
            $this->error("Invalid Extension [$className]");

            return;
        }

        call_user_func([$className, 'import'], $this);

        $this->info("Extension [$className] imported");
    }
}
