<?php

namespace Tests\Controllers;

use OpenDeveloper\Developer\Controllers\DeveloperController;
use OpenDeveloper\Developer\Form;
use OpenDeveloper\Developer\Grid;
use Tests\Models\File;

class FileController extends DeveloperController
{
    protected $title = 'Files';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new File());

        $grid->id('ID')->sortable();

        $grid->created_at();
        $grid->updated_at();

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new File());

        $form->display('id', 'ID');

        $form->file('file1');
        $form->file('file2');
        $form->file('file3');
        $form->file('file4');
        $form->file('file5');
        $form->file('file6');

        $form->display('created_at', 'Created At');
        $form->display('updated_at', 'Updated At');

        return $form;
    }
}
