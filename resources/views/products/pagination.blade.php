@if ($paginator->hasPages())
	Ver más productos <br>
    <ul class="pagination" role="navigation">

    	 
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')" style="display: inline-block;">
                <span aria-hidden="true" style="display:none;">‹</span>
            </li>
        @else
            <li  style="display: inline-block; margin:3px;padding: 12px;border:1px solid grey;">
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">‹</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled" aria-disabled="true" style="display: inline-block;">
                	 <span>{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active" aria-current="page" style="display: inline-block; padding: 12px;
    color: white;margin:3px;background-color:grey; "><span>{{ $page }}</span></li>
                    @else
                        <li style="display: inline-block;margin:3px;padding: 12px;border:1px solid grey;"><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li style="display: inline-block; margin:3px;padding: 12px;border:1px solid grey;">
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">›</a>
            </li>
        @else
            <li style="display: inline-block;" class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span aria-hidden="true" style="display: none;">›</span>
            </li>
        @endif
    </ul>
@endif