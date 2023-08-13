<?php

namespace OpenDeveloper\Developer\Widgets\Navbar;

use Illuminate\Contracts\Support\Renderable;
use OpenDeveloper\Developer\Developer;

/**
 * Class FullScreen.
 *
 * @see  https://javascript.ruanyifeng.com/htmlapi/fullscreen.html
 */
class Fullscreen implements Renderable
{
    public function render()
    {
        return Developer::component('developer::components.fullscreen');
    }
}
