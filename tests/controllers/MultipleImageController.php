<?php

namespace Tests\Controllers;

use OpenDeveloper\Developer\Controllers\DeveloperController;
use OpenDeveloper\Developer\Form;
use OpenDeveloper\Developer\Grid;
use Tests\Models\MultipleImage;

class MultipleImageController extends DeveloperController
{
    protected $title = 'Images';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MultipleImage());

        $grid->id('ID')->sortable();

        $grid->created_at();
        $grid->updated_at();

        $grid->disableFilter();

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new MultipleImage());

        $form->display('id', 'ID');

        $form->multipleImage('pictures');

        $form->display('created_at', 'Created At');
        $form->display('updated_at', 'Updated At');

        return $form;
    }
}
