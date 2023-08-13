<?php

namespace OpenDeveloper\Developer\Console;

class ControllerCommand extends MakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'developer:controller {model}
        {--title=}
        {--name=}
        {--stub= : Path to the custom stub file. }
        {--namespace=}
        {--O|output}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make developer controller (simular to developer:make)';
}
