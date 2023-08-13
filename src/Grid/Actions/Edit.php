<?php

namespace OpenDeveloper\Developer\Grid\Actions;

use OpenDeveloper\Developer\Actions\RowAction;

class Edit extends RowAction
{
    public $icon = 'icon-pen';

    /**
     * @return array|null|string
     */
    public function name()
    {
        return __('developer.edit');
    }

    /**
     * @return string
     */
    public function href()
    {
        return "{$this->getResource()}/{$this->getKey()}/edit";
    }
}
