<?php

namespace OpenDeveloper\Developer\Grid\Tools;

use OpenDeveloper\Developer\Developer;

class FilterButton extends AbstractTool
{
    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $label = '';
        $filter = $this->grid->getFilter();

        if ($scope = $filter->getCurrentScope()) {
            $label = "&nbsp;{$scope->getLabel()}&nbsp;";
        }

        return Developer::component('developer::filter.button', [
            'scopes'    => $filter->getScopes(),
            'label'     => $label,
            'cancel'    => $filter->urlWithoutScopes(),
            'btn_class' => uniqid().'-filter-btn',
            'expand'    => $filter->expand,
            'filter_id' => $filter->getFilterID(),
        ]);
    }
}
