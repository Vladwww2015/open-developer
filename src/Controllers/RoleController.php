<?php

namespace OpenDeveloper\Developer\Controllers;

use OpenDeveloper\Developer\Form;
use OpenDeveloper\Developer\Grid;
use OpenDeveloper\Developer\Show;

class RoleController extends DeveloperController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('developer.roles');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $roleModel = config('developer.database.roles_model');

        $grid = new Grid(new $roleModel());

        $grid->column('id', 'ID')->sortable();
        $grid->column('slug', trans('developer.slug'));
        $grid->column('name', trans('developer.name'));

        $grid->column('permissions', trans('developer.permission'))->pluck('name')->label();

        $grid->column('created_at', trans('developer.created_at'));
        $grid->column('updated_at', trans('developer.updated_at'));

        $grid->actions(function (Grid\Displayers\Actions\Actions $actions) {
            if ($actions->row->slug == 'administrator') {
                $actions->disableDelete();
            }
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        $roleModel = config('developer.database.roles_model');

        $show = new Show($roleModel::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('slug', trans('developer.slug'));
        $show->field('name', trans('developer.name'));
        $show->field('permissions', trans('developer.permissions'))->as(function ($permission) {
            return $permission->pluck('name');
        })->label();
        $show->field('created_at', trans('developer.created_at'));
        $show->field('updated_at', trans('developer.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $permissionModel = config('developer.database.permissions_model');
        $roleModel = config('developer.database.roles_model');

        $form = new Form(new $roleModel());

        $form->display('id', 'ID');

        $form->text('slug', trans('developer.slug'))->rules('required');
        $form->text('name', trans('developer.name'))->rules('required');
        $form->listbox('permissions', trans('developer.permissions'))->options($permissionModel::all()->pluck('name', 'id'))->height(300);

        $form->display('created_at', trans('developer.created_at'));
        $form->display('updated_at', trans('developer.updated_at'));

        return $form;
    }
}
