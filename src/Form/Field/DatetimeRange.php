<?php

namespace OpenDeveloper\Developer\Form\Field;

class DatetimeRange extends DateRange
{
    protected $format = 'YYYY-MM-DD HH:mm:ss';
    protected $view = 'developer::form.daterange';
}
