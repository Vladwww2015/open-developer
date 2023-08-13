@if(Developer::user()->visible(\Illuminate\Support\Arr::get($item, 'roles', [])) && Developer::user()->can(\Illuminate\Support\Arr::get($item, 'permission')))
    @if(!isset($item['children']))
        <li>
            @if(url()->isValidUrl($item['uri']))
                <a href="{{ $item['uri'] }}" target="_blank">
            @else
                 <a href="{{ developer_url($item['uri']) }}">
            @endif
                <i class="{{$item['icon']}}"></i>
                @if (Lang::has($titleTranslation = 'developer.menu_titles.' . trim(str_replace(' ', '_', strtolower($item['title'])))))
                    <span>{{ __($titleTranslation) }}</span>
                @else
                    <span>{{ developer_trans($item['title']) }}</span>
                @endif
            </a>
        </li>
    @else
        <li class="treeview">
            <a href="#" class="has-subs" data-bs-toggle="collapse" data-bs-target="#collapse-{{$item['id']}}" aria-expanded="false">
                <i class="{{ $item['icon'] }}"></i>
                @if (Lang::has($titleTranslation = 'developer.menu_titles.' . trim(str_replace(' ', '_', strtolower($item['title'])))))
                    <span>{{ __($titleTranslation) }}</span>
                @else
                    <span>{{ developer_trans($item['title']) }}</span>
                @endif
            </a>
            <ul id="collapse-{{$item['id']}}" class="btn-toggle-nav list-unstyled collapse fw-normal pb-1">
                @foreach($item['children'] as $item)
                    @include('developer::partials.menu', $item)
                @endforeach
            </ul>
        </li>
    @endif
@endif
