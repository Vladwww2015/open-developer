<?php

namespace OpenDeveloper\Developer\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use OpenDeveloper\Developer\Developer;

class DeveloperCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'developer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all developer commands';

    /**
     * @var string
     */
    public static $logo = <<<LOGO
   ____                             ___       __          _
  / __ \____  ___  ____            /   | ____/ /___ ___  (_)___
 / / / / __ \/ _ \/ __ \   ____   / /| |/ __  / __ `__ \/ / __ \
/ /_/ / /_/ /  __/ / / /  /___/  / ___ / /_/ / / / / / / / / / /
\____/ .___/\___/_/ /_/         /_/  |_\__,_/_/ /_/ /_/_/_/ /_/
    /_/
LOGO;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line(static::$logo);
        $this->line(Developer::getLongVersion());

        $this->comment('');
        $this->comment('Available commands:');

        $this->listDeveloperCommands();
    }

    /**
     * List all developer commands.
     *
     * @return void
     */
    protected function listDeveloperCommands()
    {
        $commands = collect(Artisan::all())->mapWithKeys(function ($command, $key) {
            if (Str::startsWith($key, 'developer:')) {
                return [$key => $command];
            }

            return [];
        })->toArray();

        $width = $this->getColumnWidth($commands);

        /** @var Command $command */
        foreach ($commands as $command) {
            $this->line(sprintf(" %-{$width}s %s", $command->getName(), $command->getDescription()));
        }
    }

    /**
     * @param (Command|string)[] $commands
     *
     * @return int
     */
    private function getColumnWidth(array $commands)
    {
        $widths = [];

        foreach ($commands as $command) {
            $widths[] = static::strlen($command->getName());
            foreach ($command->getAliases() as $alias) {
                $widths[] = static::strlen($alias);
            }
        }

        return $widths ? max($widths) + 2 : 0;
    }

    /**
     * Returns the length of a string, using mb_strwidth if it is available.
     *
     * @param string $string The string to check its length
     *
     * @return int The length of the string
     */
    public static function strlen($string)
    {
        if (false === $encoding = mb_detect_encoding($string, null, true)) {
            return strlen($string);
        }

        return mb_strwidth($string, $encoding);
    }
}
