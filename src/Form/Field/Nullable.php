<?php

namespace OpenDeveloper\Developer\Form\Field;

use OpenDeveloper\Developer\Form\Field;

class Nullable extends Field
{
    public function __construct()
    {
    }

    public function __call($method, $parameters)
    {
        return $this;
    }
}
