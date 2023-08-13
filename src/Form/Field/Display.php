<?php

namespace OpenDeveloper\Developer\Form\Field;

use OpenDeveloper\Developer\Form\Field;

class Display extends Field
{
    public function prepare($value)
    {
        return $this->original();
    }
}
