<?php

namespace OpenDeveloper\Developer\Grid\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OpenDeveloper\Developer\Actions\Response;
use OpenDeveloper\Developer\Actions\RowAction;

class Delete extends RowAction
{
    public $icon = 'icon-trash';

    /**
     * @return array|null|string
     */
    public function name()
    {
        return __('developer.delete');
    }

    public function addScript()
    {
        $this->attributes = [
            'onclick' => 'developer.resource.delete(event,this)',
            'data-url'=> "{$this->getResource()}/{$this->getKey()}",
        ];
    }

    /*
    // could use dialog as well instead of addScript
    public function dialog()
    {
        $options  = [
            "type" => "warning",
            "showCancelButton"=> true,
            "confirmButtonColor"=> "#DD6B55",
            "confirmButtonText"=> __('confirm'),
            "showLoaderOnConfirm"=> true,
            "cancelButtonText"=>  __('cancel'),
        ];
        $this->confirm('Are you sure delete?', '', $options);
    }
    */

    /**
     * @param Model $model
     *
     * @return Response
     */
    public function handle(Model $model)
    {
        $trans = [
            'failed'    => trans('developer.delete_failed'),
            'succeeded' => trans('developer.delete_succeeded'),
        ];

        try {
            DB::transaction(function () use ($model) {
                $model->delete();
            });
        } catch (\Exception $exception) {
            return $this->response()->error("{$trans['failed']} : {$exception->getMessage()}");
        }

        return $this->response()->success($trans['succeeded'])->refresh();
    }
}
