<?php

namespace OpenDeveloper\Developer\Grid\Displayers;

use Illuminate\Support\Arr;
use OpenDeveloper\Developer\Developer;

class Checkbox extends AbstractDisplayer
{
    public function display($options = [])
    {
        return Developer::component('developer::grid.inline-edit.checkbox', [
            'key'      => $this->getKey(),
            'name'     => $this->getPayloadName(),
            'resource' => $this->getResource(),
            'trigger'  => "ie-trigger-{$this->getClassName()}-{$this->getKey()}",
            'target'   => "ie-content-{$this->getClassName()}-{$this->getKey()}",
            'value'    => $this->getValue(),
            'display'  => implode(',', Arr::only($options, json_decode($this->getValue()))),
            'options'  => $options,
        ]);
    }
}
