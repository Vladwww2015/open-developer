<?php

namespace OpenDeveloper\Developer\Grid\Displayers;

use OpenDeveloper\Developer\Developer;

class Upload extends AbstractDisplayer
{
    public function display($multiple = false)
    {
        return Developer::component('developer::grid.inline-edit.upload', [
            'key'      => $this->getKey(),
            'name'     => $this->getPayloadName(),
            'value'    => $this->getValue(),
            'target'   => "inline-upload-{$this->getKey()}",
            'resource' => $this->getResource(),
            'multiple' => $multiple,
        ]);
    }
}
