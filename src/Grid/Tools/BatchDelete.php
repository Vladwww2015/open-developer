<?php

namespace OpenDeveloper\Developer\Grid\Tools;

use OpenDeveloper\Developer\Actions\BatchAction;

class BatchDelete extends BatchAction
{
    public $icon = 'icon-trash';

    public function __construct()
    {
        $this->name = trans('developer.batch_delete');
    }

    /**
     * Script of batch delete action.
     */
    public function script()
    {
        return <<<JS
        document.querySelector('{$this->getSelector()}').addEventListener("click",function(){
            let resource_url = '{$this->resource}/' + developer.grid.selected.join();
            developer.resource.batch_delete(resource_url);
        });
JS;
    }
}
