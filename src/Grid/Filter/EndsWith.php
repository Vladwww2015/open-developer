<?php

namespace OpenDeveloper\Developer\Grid\Filter;

class EndsWith extends Like
{
    protected $exprFormat = '%{value}';
}
