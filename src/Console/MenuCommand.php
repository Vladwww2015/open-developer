<?php

namespace OpenDeveloper\Developer\Console;

use Illuminate\Console\Command;
use OpenDeveloper\Developer\Facades\Developer;

class MenuCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'developer:menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show the developer menu';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $menu = Developer::menu();

        echo json_encode($menu, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), "\r\n";
    }
}
