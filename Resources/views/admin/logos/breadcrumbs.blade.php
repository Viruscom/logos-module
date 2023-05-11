<div class="breadcrumbs">
    <ul>
        <li>
            <a href="{{ route('admin.index') }}"><i class="fa fa-home"></i></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('admin.logos.index') }}" class="text-black">@lang('logos::admin.logos.index')</a>
        </li>
        @if(url()->current() === route('admin.logos.toManyPagesCreate'))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.logos.toManyPagesCreate') }}" class="text-purple">@lang('logos::admin.logos.to_many_pages_create')</a>
            </li>
        @elseif(!is_null(Request::segment(4)) && url()->current() === route('admin.logos.create', ['path' => Request::segment(4)]))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.logos.create', ['path' => Request::segment(4)]) }}" class="text-purple">@lang('logos::admin.logos.create')</a>
            </li>
        @elseif(Request::segment(3) !== null && url()->current() === route('admin.logos.edit', ['id' => Request::segment(3)]))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.logos.edit', ['id' => Request::segment(3)]) }}" class="text-purple">@lang('logos::admin.logos.edit')</a>
            </li>
        @endif
    </ul>
</div>
