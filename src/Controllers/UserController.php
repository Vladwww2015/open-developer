<?php

namespace OpenDeveloper\Developer\Controllers;

use Illuminate\Support\Facades\Hash;
use OpenDeveloper\Developer\Form;
use OpenDeveloper\Developer\Grid;
use OpenDeveloper\Developer\Show;

class UserController extends DeveloperController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('developer.administrator');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $userModel = config('developer.database.users_model');

        $grid = new Grid(new $userModel());

        $grid->column('id', 'ID')->sortable();
        $grid->column('username', trans('developer.username'));
        $grid->column('name', trans('developer.name'));
        $grid->column('roles', trans('developer.roles'))->pluck('name')->label();
        $grid->column('created_at', trans('developer.created_at'));
        $grid->column('updated_at', trans('developer.updated_at'));

        $grid->actions(function (Grid\Displayers\Actions\Actions $actions) {
            if ($actions->getKey() == 1) {
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
        $userModel = config('developer.database.users_model');

        $show = new Show($userModel::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('username', trans('developer.username'));
        $show->field('name', trans('developer.name'));
        $show->field('roles', trans('developer.roles'))->as(function ($roles) {
            return $roles->pluck('name');
        })->label();
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
        $userModel = config('developer.database.users_model');
        $permissionModel = config('developer.database.permissions_model');
        $roleModel = config('developer.database.roles_model');

        $form = new Form(new $userModel());

        $userTable = config('developer.database.users_table');
        $connection = config('developer.database.connection');

        $form->display('id', 'ID');
        $form->text('username', trans('developer.username'))
            ->creationRules(['required', "unique:{$connection}.{$userTable}"])
            ->updateRules(['required', "unique:{$connection}.{$userTable},username,{{id}}"]);

        $form->text('name', trans('developer.name'))->rules('required');
        $form->image('avatar', trans('developer.avatar'));
        $form->password('password', trans('developer.password'))->rules('required|confirmed');
        $form->password('password_confirmation', trans('developer.password_confirmation'))->rules('required')
            ->default(function ($form) {
                return $form->model()->password;
            });

        $form->ignore(['password_confirmation']);

        $form->multipleSelect('roles', trans('developer.roles'))->options($roleModel::all()->pluck('name', 'id'));
        $form->multipleSelect('permissions', trans('developer.permissions'))->options($permissionModel::all()->pluck('name', 'id'));

        $form->display('created_at', trans('developer.created_at'));
        $form->display('updated_at', trans('developer.updated_at'));

        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = Hash::make($form->password);
            }
        });

        return $form;
    }
}
