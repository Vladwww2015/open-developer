<?php

namespace OpenDeveloper\Developer\Grid\Selectable;

use Illuminate\Contracts\Support\Renderable;

class BrowserBtn implements Renderable
{
    public function render()
    {
        $text = developer_trans('developer.choose');

        $html = <<<HTML
<a href="javascript:void(0)" class="btn btn-primary btn-sm pull-left select-relation">
    <i class="icon-folder-open"></i>
    &nbsp;&nbsp;{$text}
</a>
HTML;

        return $html;
    }
}
