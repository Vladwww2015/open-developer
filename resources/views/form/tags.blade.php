@include("developer::form._header")

    <input class="form-control {{$class}}" name="{{$name}}[]" data-placeholder="{{ $placeholder }}" {!! $attributes !!} value="{{implode(",",$value)}}" />

@include("developer::form._footer")
