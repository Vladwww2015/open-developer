<?php

namespace OpenDeveloper\Developer\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use MatthiasMullie\Minify;
use OpenDeveloper\Developer\Developer;
use OpenDeveloper\Developer\Facades\Developer as DeveloperFacade;

class MinifyCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'developer:minify {--clear}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Minify the CSS and JS';

    /**
     * @var array
     */
    protected $assets = [
        'css' => [],
        'js'  => [],
    ];

    /**
     * @var array
     */
    protected $excepts = [];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!class_exists(Minify\Minify::class)) {
            $this->error('To use `developer:minify` command, please install [matthiasmullie/minify] first.');

            return;
        }

        if ($this->option('clear')) {
            return $this->clearMinifiedFiles();
        }

        DeveloperFacade::bootstrap();

        $this->loadExcepts();

        $this->minifyCSS();
        $this->minifyJS();

        $this->generateManifest();

        $this->comment('JS and CSS are successfully minified:');
        $this->line('  '.Developer::$min['js']);
        $this->line('  '.Developer::$min['css']);

        $this->line('');

        $this->comment('Manifest successfully generated:');
        $this->line('  '.Developer::$manifest);
    }

    protected function loadExcepts()
    {
        $excepts = config('developer.minify_assets.excepts', []);

        $this->excepts = array_merge($excepts, Developer::$minifyIgnoresCss, Developer::$minifyIgnoresJs);
    }

    protected function clearMinifiedFiles()
    {
        @unlink(public_path(Developer::$manifest));
        @unlink(public_path(Developer::$min['js']));
        @unlink(public_path(Developer::$min['css']));

        $this->comment('Following files are cleared:');

        $this->line('  '.Developer::$min['js']);
        $this->line('  '.Developer::$min['css']);
        $this->line('  '.Developer::$manifest);
    }

    protected function minifyCSS()
    {
        $css = collect(array_merge(Developer::$css, Developer::baseCss()))
            ->unique()->map(function ($css) {
                if (url()->isValidUrl($css)) {
                    $this->assets['css'][] = $css;

                    return;
                }

                if (in_array($css, $this->excepts)) {
                    $this->assets['css'][] = $css;

                    return;
                }

                if (Str::contains($css, '?')) {
                    $css = substr($css, 0, strpos($css, '?'));
                }

                return public_path($css);
            })->filter();

        $minifier = new Minify\CSS();

        $minifier->add(...$css);

        $minifier->minify(public_path(Developer::$min['css']));
    }

    protected function minifyJS()
    {
        $js = collect(array_merge(Developer::$js, Developer::baseJs()))
            ->unique()->map(function ($js) {
                if (url()->isValidUrl($js)) {
                    $this->assets['js'][] = $js;

                    return;
                }

                if (in_array($js, $this->excepts)) {
                    $this->assets['js'][] = $js;

                    return;
                }

                if (Str::contains($js, '?')) {
                    $js = substr($js, 0, strpos($js, '?'));
                }

                return public_path($js);
            })->filter();

        $minifier = new Minify\JS();

        $minifier->add(...$js);

        $minifier->minify(public_path(Developer::$min['js']));
    }

    protected function generateManifest()
    {
        $min = collect(Developer::$min)->mapWithKeys(function ($path, $type) {
            return [$type => sprintf('%s?id=%s', $path, md5(uniqid()))];
        });

        array_unshift($this->assets['css'], $min['css']);
        array_unshift($this->assets['js'], $min['js']);

        $json = json_encode($this->assets, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        file_put_contents(public_path(Developer::$manifest), $json);
    }
}
