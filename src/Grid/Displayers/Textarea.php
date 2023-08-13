<?php

namespace OpenDeveloper\Developer\Grid\Displayers;

use OpenDeveloper\Developer\Developer;

class Textarea extends AbstractDisplayer
{
    public function display($rows = 5)
    {
        return Developer::component('developer::grid.inline-edit.textarea', [
            'key'      => $this->getKey(),
            'value'    => $this->getValue(),
            'display'  => $this->getValue(),
            'name'     => $this->getPayloadName(),
            'resource' => $this->getResource(),
            'trigger'  => "ie-trigger-{$this->getClassName()}-{$this->getKey()}",
            'target'   => "ie-content-{$this->getClassName()}-{$this->getKey()}",
            'rows'     => $rows,
        ]);
    }
}
