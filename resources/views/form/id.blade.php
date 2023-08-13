@include("developer::form._header")

        <input type="text" id="{{$id}}" name="{{$name}}" value="{{$value}}" class="form-control" readonly {!! $attributes !!} />

@include("developer::form._footer")
