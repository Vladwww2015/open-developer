
<template render="true">
    <div class="modal fade picker" id="{{ $modal }}" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="border-radius: 5px;">
                <div class="modal-header">
                    <h4 class="modal-title">{{ developer_trans('developer.choose') }}</h4>
                    <button type="button" class="btn btn-light close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="loading text-center">
                        <div class="icon-pulse">
                            <i class="icon-spinner icon-3x icon-fw"></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display:none;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ developer_trans('developer.cancel') }}</button>
                    <button type="button" class="btn btn-primary submit">{{ developer_trans('developer.submit') }}</button>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

    window.setFile{{$selector}} = function (url,fileName){
        FileUpload_{{$name}}.addFileFromUrl(url);

        @if (empty($multiple))
            developer.selectable.hideModal();
        @else
            developer.toast("File added");
        @endif
    }

    var url = "/developer/media?select=true&fn=setFile{{$selector}}{!!$picker_path!!}";
    var config = {
        url : url,
        modal_elm : document.querySelector('#{{$modal}}'),
    }
    developer.selectable.init(config);


</script>

<style>
    #{{$modal}} .card-header.navbar{
        display:none;
    }

</style>
