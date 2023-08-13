@include("developer::form._header")

	<textarea name="{{$name}}" class="form-control {{$class}}" rows="{{ $rows }}" placeholder="{{ $placeholder }}" {!! $attributes !!} >{{ old($column, $value) }}</textarea>

	{!! $append !!}

@include("developer::form._footer")
