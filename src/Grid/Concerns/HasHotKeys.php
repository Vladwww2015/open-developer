<?php

namespace OpenDeveloper\Developer\Grid\Concerns;

use OpenDeveloper\Developer\Developer;

trait HasHotKeys
{
    protected function addHotKeyScript()
    {
        $filterID = $this->getFilter()->getFilterID();

        $refreshMessage = __('developer.refresh_succeeded');

        $script = <<<'SCRIPT'

            developer.grid.hotkeys();


SCRIPT;

        Developer::script($script);
    }

    public function enableHotKeys()
    {
        $this->addHotKeyScript();

        return $this;
    }
}
