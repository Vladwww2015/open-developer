@extends('developer::grid.inline-edit.comm')
@php
    $type = "textarea";
@endphp

@section('field')
    <textarea class="form-control ie-input" rows="{{ $rows }}">{{$value}}</textarea>
@endsection

@section('assert')
    <script>
       developer.grid.inline_edit.functions['{{ $trigger }}'] = {
            content : function(trigger,content){
                //content.querySelector('select').value = trigger.dataset.value;
            },
            shown : function(trigger,content){
            },
            returnValue : function(trigger,content){
            }
        }
    </script>

    {{--after submit--}}
    <script>
    @component('developer::grid.inline-edit.partials.submit', compact('resource', 'name'))
        $popover.data('display').html(val);
    @endcomponent
    </script>
@endsection


