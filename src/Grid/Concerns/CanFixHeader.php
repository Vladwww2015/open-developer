<?php

namespace OpenDeveloper\Developer\Grid\Concerns;

use OpenDeveloper\Developer\Developer;

trait CanFixHeader
{
    public function fixHeader()
    {
        Developer::style(
            <<<'STYLE'
.wrapper, .table-responsive {
    overflow: visible;
}

.grid-table {
    position: relative;
    border-collapse: separate;
    border-spacing: 0;
    background:#FFF;
}

.grid-table thead tr:first-child th {
    background: white;
    position: sticky;
    top: 0;
    z-index: 1;
}
STYLE
        );
    }
}
