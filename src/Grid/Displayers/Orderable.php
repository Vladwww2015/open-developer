<?php

namespace OpenDeveloper\Developer\Grid\Displayers;

use OpenDeveloper\Developer\Developer;

class Orderable extends AbstractDisplayer
{
    public function display()
    {
        if (!trait_exists('\Spatie\EloquentSortable\SortableTrait')) {
            throw new \Exception('To use orderable grid, please install package [spatie/eloquent-sortable] first.');
        }

        Developer::script($this->script());

        return <<<EOT

<div class="btn-group">
    <button type="button" class="btn btn-xs btn-light {$this->grid->getGridRowName()}-orderable" data-id="{$this->getKey()}" data-direction="1">
        <i class="icon-caret-up fa-fw"></i>
    </button>
    <button type="button" class="btn btn-xs btn-light {$this->grid->getGridRowName()}-orderable" data-id="{$this->getKey()}" data-direction="0">
        <i class="icon-caret-down fa-fw"></i>
    </button>
</div>

EOT;
    }

    protected function script()
    {
        return <<<JS

document.querySelectorAll('.{$this->grid->getGridRowName()}-orderable').forEach(el => {
    el.addEventListener('click', function(event) {

        var key = this.dataset.id;
        var direction = this.dataset.direction;
        var url = '{$this->getResource()}/' + key;
        var data = {
            _method:'PUT',
            _token:LA.token,
            _orderable:direction
        };

        developer.ajax.post(url, data, function(data){

            if (data.status) {
                developer.ajax.reload();
                developer.toastr.success(data.message);
            }
        });
    });

});
JS;
    }
}
