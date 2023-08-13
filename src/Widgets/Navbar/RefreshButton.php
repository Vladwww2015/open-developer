<?php

namespace OpenDeveloper\Developer\Widgets\Navbar;

use Illuminate\Contracts\Support\Renderable;
use OpenDeveloper\Developer\Developer;

class RefreshButton implements Renderable
{
    public function render()
    {
        return Developer::component('developer::components.refresh-btn');
    }
}
