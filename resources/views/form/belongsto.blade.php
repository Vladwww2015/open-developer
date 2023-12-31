@include("developer::form._header")

    <select class="form-select {{$class}} d-none" style="width: 100%;" name="{{$name}}" {!! $attributes !!} >
        <option value=""></option>
        @foreach($options as $select => $option)
            <option value="{{$select}}" {{ $select == old($column, $value) ?'selected':'' }}>{{$option}}</option>
        @endforeach
    </select>

    <div class="belongsto-{{ $class }} belongsto belongsto-selected-rows">

        {!! $grid->render() !!}

        <template class="empty">
            @include('developer::grid.empty-grid')
        </template>
    </div>

@include("developer::form._footer")
