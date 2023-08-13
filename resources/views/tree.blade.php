<div class="card">

    <div class="card-header">

        <div class="btn-group">
            <a class="btn btn-primary btn-sm {{ $id }}-tree-tools" data-action="expand" title="{{ trans('developer.expand') }}" onclick="developer.tree.expand();">
                <i class="icon-plus-square"></i>&nbsp;{{ trans('developer.expand') }}
            </a>
            <a class="btn btn-primary btn-sm {{ $id }}-tree-tools" data-action="collapse" title="{{ trans('developer.collapse') }}" onclick="developer.tree.collapse();">
                <i class="icon-minus-square"></i>&nbsp;{{ trans('developer.collapse') }}
            </a>
        </div>

        @if($useSave)
        <div class="btn-group">
            <a class="btn btn-info btn-sm {{ $id }}-save" title="{{ trans('developer.save') }}" onclick="developer.tree.save();"><i class="icon-save"></i><span class="hidden-xs">&nbsp;{{ trans('developer.save') }}</span></a>
        </div>
        @endif

        @if($useRefresh)
        <div class="btn-group">
            <a class="btn btn-warning btn-sm {{ $id }}-refresh" title="{{ trans('developer.refresh') }}" onclick="developer.ajax.reload();"><i class="icon-refresh"></i><span class="hidden-xs">&nbsp;{{ trans('developer.refresh') }}</span></a>
        </div>
        @endif

        <div class="btn-group">
            {!! $tools !!}
        </div>

        @if($useCreate)
        <div class="btn-group pull-right">
            <a class="btn btn-success btn-sm" href="{{ url($path) }}/create"><i class="icon-save"></i><span class="hidden-xs">&nbsp;{{ trans('developer.new') }}</span></a>
        </div>
        @endif

    </div>
    <!-- /.box-header -->
    <div class="card-body table-responsive no-padding">
        <div class="dd" id="{{ $id }}">
            <ol class="dd-list">
                @each($branchView, $items, 'branch')
            </ol>
        </div>
    </div>
    <!-- /.box-body -->
</div>
