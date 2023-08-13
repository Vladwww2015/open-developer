<?php

namespace OpenDeveloper\Developer\Grid\Tools;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use OpenDeveloper\Developer\Developer;
use OpenDeveloper\Developer\Form;
use OpenDeveloper\Developer\Form\Field;
use OpenDeveloper\Developer\Grid;

class QuickCreate implements Renderable
{
    /**
     * @var Grid
     */
    protected $parent;

    /**
     * @var Collection
     */
    protected $fields;

    /**
     * QuickCreate constructor.
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->parent = $grid;
        $this->fields = Collection::make();
        $this->form = new Form($grid->model());
    }

    /**
     * @param Field $field
     *
     * @return Field
     */
    protected function addField(Field $field)
    {
        $elementClass = array_merge(['quick-create', 'form-control-sm'], $field->getElementClass());

        $field->addElementClass($elementClass);
        $field->setInline(true);
        $this->fields->push($field);

        return $field;
    }

    protected function script()
    {
        $url = $this->parent->resource();

        $script = <<<'JS'
document.querySelector('.quick-create .create').addEventListener('click',function () {
    show(document.querySelector('.quick-create .create-form'),'flex');
    hide(this);
});
document.querySelector('.quick-create .cancel').addEventListener('click',function () {
    hide(document.querySelector('.quick-create .create-form'));
    show(document.querySelector('.quick-create .create'));
});

document.querySelector('.quick-create .create-form').addEventListener('submit',function (e) {

    e.preventDefault();
    var form = this;
    developer.form.submit(form,function(data){
        if (data.status == 200) {
            developer.toastr.success("Saved",{positionClass:"toast-top-center"});
            developer.ajax.reload();
            return;
        }

        if (typeof data.validation !== 'undefined') {
            developer.toastr.warning(data.message, {positionClass:"toast-top-center"})
        }
    });
    return false;
});
JS;

        Developer::script($script);
    }

    /**
     * @param int $columnCount
     *
     * @return array|string
     */
    public function render($columnCount = 0)
    {
        if ($this->fields->isEmpty()) {
            return '';
        }

        $this->script();

        $vars = [
            'columnCount' => $columnCount,
            'fields'      => $this->fields,
            'url'         => $this->parent->resource(),
        ];

        return view('developer::grid.quick-create-form', $vars)->render();
    }

    /**
     * Add nested-form fields dynamically.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if ($className = Form::findFieldClass($method)) {
            $column = Arr::get($arguments, 0, '');

            /* @var Field $field */
            $field = new $className($column, array_slice($arguments, 1));
            $field->setForm($this->form);
            $field = $this->addField($field);

            return $field;
        }

        return $this;
    }
}
