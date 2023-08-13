<?php

namespace OpenDeveloper\Developer\Controllers;

use Illuminate\Routing\Controller;
use OpenDeveloper\Developer\Form;
use OpenDeveloper\Developer\Layout\Column;
use OpenDeveloper\Developer\Layout\Content;
use OpenDeveloper\Developer\Layout\Row;
use OpenDeveloper\Developer\Tree;
use OpenDeveloper\Developer\Widgets\Box;

class MenuController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->title(trans('developer.menu'))
            ->description(trans('developer.list'))
            ->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \OpenDeveloper\Developer\Widgets\Form();
                    $form->action(developer_url('auth/menu'));

                    $menuModel = config('developer.database.menu_model');
                    $permissionModel = config('developer.database.permissions_model');
                    $roleModel = config('developer.database.roles_model');

                    $form->select('parent_id', trans('developer.parent_id'))->options($menuModel::selectOptions());
                    $form->text('title', trans('developer.title'))->rules('required');
                    $form->icon('icon', trans('developer.icon'))->default('fa-bars')->rules('required')->help($this->iconHelp());
                    $form->text('uri', trans('developer.uri'));
                    $form->multipleSelect('roles', trans('developer.roles'))->options($roleModel::all()->pluck('name', 'id'));
                    if ((new $menuModel())->withPermission()) {
                        $form->select('permission', trans('developer.permission'))->options($permissionModel::pluck('name', 'slug'));
                    }
                    $form->hidden('_token')->default(csrf_token());

                    $column->append((new Box(trans('developer.new'), $form))->style('success'));
                });
            });
    }

    /**
     * Redirect to edit page.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        return redirect()->route('developer.auth.menu.edit', ['menu' => $id]);
    }

    /**
     * @return \OpenDeveloper\Developer\Tree
     */
    protected function treeView()
    {
        $menuModel = config('developer.database.menu_model');

        $tree = new Tree(new $menuModel());

        $tree->disableCreate();

        $tree->branch(function ($branch) {
            $payload = "<i class='{$branch['icon']}'></i>&nbsp;<strong>{$branch['title']}</strong>";

            if (!isset($branch['children'])) {
                if (url()->isValidUrl($branch['uri'])) {
                    $uri = $branch['uri'];
                } else {
                    $uri = developer_url($branch['uri']);
                }

                $payload .= "&nbsp;&nbsp;&nbsp;<a href=\"$uri\" class=\"dd-nodrag\">$uri</a>";
            }

            return $payload;
        });

        return $tree;
    }

    /**
     * Edit interface.
     *
     * @param string  $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->title(trans('developer.menu'))
            ->description(trans('developer.edit'))
            ->row($this->form()->edit($id));
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $menuModel = config('developer.database.menu_model');
        $permissionModel = config('developer.database.permissions_model');
        $roleModel = config('developer.database.roles_model');

        $form = new Form(new $menuModel());

        $form->display('id', 'ID');

        $form->select('parent_id', trans('developer.parent_id'))->options($menuModel::selectOptions());
        $form->text('title', trans('developer.title'))->rules('required');
        $form->icon('icon', trans('developer.icon'))->default('fa-bars')->rules('required')->help($this->iconHelp());
        $form->text('uri', trans('developer.uri'));
        $form->multipleSelect('roles', trans('developer.roles'))->options($roleModel::all()->pluck('name', 'id'));
        if ($form->model()->withPermission()) {
            $form->select('permission', trans('developer.permission'))->options($permissionModel::pluck('name', 'slug'));
        }

        $form->display('created_at', trans('developer.created_at'));
        $form->display('updated_at', trans('developer.updated_at'));

        return $form;
    }

    /**
     * Help message for icon field.
     *
     * @return string
     */
    protected function iconHelp()
    {
        return 'For more icons please see <a href="http://fontawesome.io/icons/" target="_blank">http://fontawesome.io/icons/</a>';
    }
}
