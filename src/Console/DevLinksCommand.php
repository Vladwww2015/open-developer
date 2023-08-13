<?php

namespace OpenDeveloper\Developer\Console;

use Illuminate\Console\Command;
use ReflectionClass;

class DevLinksCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'developer:dev-links
                {--relative : Create the symbolic link using relative paths}
                {--force : Recreate existing symbolic links}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a symbolic link from the open-developer/resources/assets dir to public/vendor/open-developer for ease of development';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $relative = $this->option('relative');

        foreach ($this->links() as $link => $target) {
            if (file_exists($link) && !$this->isRemovableSymlink($link, $this->option('force'))) {
                $this->error("The [$link] link already exists.");
                continue;
            }

            if (is_link($link)) {
                $this->laravel->make('files')->delete($link);
            }

            if ($relative) {
                $this->laravel->make('files')->relativeLink($target, $link);
            } else {
                $this->laravel->make('files')->link($target, $link);
            }

            $this->info("The [$link] link has been connected to [$target].");
        }

        $this->info('The links have been created.');
    }

    /**
     * Get the symbolic links that are configured for the application.
     *
     * @return array
     */
    protected function links()
    {
        $reflector = new ReflectionClass("\OpenDeveloper\Developer\Developer");
        $dir = str_replace('src/Developer.php', '', $reflector->getFileName()).'resources/assets/';

        return [public_path('vendor/open-developer_') => $dir];
    }

    /**
     * Determine if the provided path is a symlink that can be removed.
     *
     * @param string $link
     * @param bool   $force
     *
     * @return bool
     */
    protected function isRemovableSymlink(string $link, bool $force): bool
    {
        return is_link($link) && $force;
    }
}
