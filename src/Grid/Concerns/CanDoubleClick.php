<?php

namespace OpenDeveloper\Developer\Grid\Concerns;

use OpenDeveloper\Developer\Developer;

trait CanDoubleClick
{
    /**
     * Double-click grid row to jump to the edit page.
     *
     * @return $this
     */
    public function enableDblClick()
    {
        $script = <<<SCRIPT
        document.body.addEventListener('dblclick', function (e) {
            tr = e.target.closest("tr");
            if (tr && tr.dataset.key){
                var url = "{$this->resource()}/"+tr.dataset.key+"/edit";
                developer.ajax.navigate(url);
            }
        });
SCRIPT;
        Developer::script($script);

        return $this;
    }
}
