<?php

namespace OpenDeveloper\Developer\Form\Field\Traits;

use OpenDeveloper\Developer\Developer;
use OpenDeveloper\Developer\Form\Field;

/**
 * @mixin Field
 */
trait HasMediaPicker
{
    public $picker = false;
    public $picker_path = '';

    /**
     * @param string $picker
     * @param string $column
     *
     * @return $this
     */
    public function pick($path = '')
    {
        if ($path != '') {
            $this->picker_path = '&path='.$path;
        }
        $this->picker = 'one';
        $this->retainable(true);

        return $this;
    }

    /**
     * @param \Closure|null $callback
     */
    protected function addPickBtn(\Closure $callback = null)
    {
        $text = developer_trans('developer.choose');

        $btn = <<<HTML
            <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#{$this->modal}">
                <i class="icon-folder-open"></i>  {$text}
            </a>
        HTML;

        if ($callback) {
            $callback($btn);
        } else {
            $this->addVariables(compact('btn'));
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    protected function renderMediaPicker()
    {
        if (!class_exists("OpenDeveloper\Developer\Media\MediaManager")) {
            throw new \Exception(
                '[Media Manager extention not installed yet.<br> Install using: <b>composer require open-developer-ext/media-manager</b><br><br>'
            );
        }

        $this->modal = sprintf('media-picker-modal-%s', $this->getElementClassString());
        $this->addVariables([
            'modal'       => $this->modal,
            'selector'    => $this->getElementClassString(),
            'name'        => $this->formatName($this->column),
            'multiple'    => !empty($this->multiple),
            'picker_path' => $this->picker_path,
            'trans'       => [
                'choose' => developer_trans('developer.choose'),
                'cancal' => developer_trans('developer.cancel'),
                'submit' => developer_trans('developer.submit'),
            ],
        ]);

        $this->addPickBtn();

        return Developer::component('developer::components.mediapicker', $this->variables());
    }
}
