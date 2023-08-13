<?php

namespace OpenDeveloper\Developer\Grid\Displayers;

use Illuminate\Support\Arr;
use OpenDeveloper\Developer\Developer;

class SwitchDisplay extends AbstractDisplayer
{
    /**
     * @var array
     */
    protected $states = [
        'on'  => ['value' => 1, 'text' => 'ON', 'color' => 'primary'],
        'off' => ['value' => 0, 'text' => 'OFF', 'color' => 'default'],
    ];

    protected function overrideStates($states)
    {
        if (empty($states)) {
            return;
        }

        foreach (Arr::dot($states) as $key => $state) {
            Arr::set($this->states, $key, $state);
        }
    }

    public function display($states = [])
    {
        $this->overrideStates($states);

        return Developer::component('developer::grid.inline-edit.switch', [
            'class'    => 'grid-switch-'.str_replace('.', '-', $this->getName()),
            'key'      => $this->getKey(),
            'resource' => $this->getResource(),
            'name'     => $this->getPayloadName(),
            'states'   => $this->states,
            'checked'  => $this->states['on']['value'] == $this->getValue() ? 'checked' : '',
        ]);
    }
}
