<?php

namespace OpenDeveloper\Developer\Controllers;

use Illuminate\Support\Str;
use OpenDeveloper\Developer\Form;
use OpenDeveloper\Developer\Grid;
use OpenDeveloper\Developer\Show;

class PermissionController extends DeveloperController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('developer.permissions');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $permissionModel = config('developer.database.permissions_model');

        $grid = new Grid(new $permissionModel());

        $grid->column('id', 'ID')->sortable();
        $grid->column('slug', trans('developer.slug'));
        $grid->column('name', trans('developer.name'));

        $grid->column('http_path', trans('developer.route'))->display(function ($path) {
            return collect(explode("\n", $path))->map(function ($path) {
                $method = $this->http_method ?: ['ANY'];

                if (Str::contains($path, ':')) {
                    list($method, $path) = explode(':', $path);
                    $method = explode(',', $method);
                }

                $method = collect($method)->map(function ($name) {
                    return strtoupper($name);
                })->map(function ($name) {
                    return "<span class='badge bg-primary'>{$name}</span>";
                })->implode('&nbsp;');

                if (!empty(config('developer.route.prefix'))) {
                    $path = '/'.trim(config('developer.route.prefix'), '/').$path;
                }

                return "<div style='margin-bottom: 5px;'>$method<code>$path</code></div>";
            })->implode('');
        });

        $grid->column('created_at', trans('developer.created_at'));
        $grid->column('updated_at', trans('developer.updated_at'));

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
        $permissionModel = config('developer.database.permissions_model');

        $show = new Show($permissionModel::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('slug', trans('developer.slug'));
        $show->field('name', trans('developer.name'));

        $show->field('http_path', trans('developer.route'))->unescape()->as(function ($path) {
            return collect(explode("\r\n", $path))->map(function ($path) {
                $method = $this->http_method ?: ['ANY'];

                if (Str::contains($path, ':')) {
                    list($method, $path) = explode(':', $path);
                    $method = explode(',', $method);
                }

                $method = collect($method)->map(function ($name) {
                    return strtoupper($name);
                })->map(function ($name) {
                    return "<span class='badge bg-primary'>{$name}</span>";
                })->implode('&nbsp;');

                if (!empty(config('developer.route.prefix'))) {
                    $path = '/'.trim(config('developer.route.prefix'), '/').$path;
                }

                return "<div style='margin-bottom: 5px;'>$method<code>$path</code></div>";
            })->implode('');
        });

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

        $form = new Form(new $permissionModel());

        $form->display('id', 'ID');

        $form->text('slug', trans('developer.slug'))->rules('required');
        $form->text('name', trans('developer.name'))->rules('required');

        $form->multipleSelect('http_method', trans('developer.http.method'))
            ->options($this->getHttpMethodsOptions())
            ->help(trans('developer.all_methods_if_empty'));
        $form->textarea('http_path', trans('developer.http.path'));

        $form->display('created_at', trans('developer.created_at'));
        $form->display('updated_at', trans('developer.updated_at'));

        return $form;
    }

    /**
     * Get options of HTTP methods select field.
     *
     * @return array
     */
    protected function getHttpMethodsOptions()
    {
        $model = config('developer.database.permissions_model');

        return array_combine($model::$httpMethods, $model::$httpMethods);
    }
}
