<?php

namespace OpenDeveloper\Developer\Grid\Actions;

use OpenDeveloper\Developer\Actions\RowAction;

class Show extends RowAction
{
    public $icon = 'icon-eye';

    /**
     * @return array|null|string
     */
    public function name()
    {
        return __('developer.show');
    }

    /**
     * @return string
     */
    public function href()
    {
        return "{$this->getResource()}/{$this->getKey()}";
    }
}
