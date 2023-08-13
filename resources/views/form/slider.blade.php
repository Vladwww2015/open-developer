@include("developer::form._header")

    <input type="range" class="{{$class}} form-range" name="{{$name}}" data-from="{{ old($column, $value) }}" {!! $attributes !!} />

@include("developer::form._footer")
