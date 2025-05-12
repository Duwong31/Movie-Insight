@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="custom-pagination-nav">
        <ul class="custom-pagination">
            {{-- Nút Previous --}}
            @if ($paginator->onFirstPage())
                <li class="custom-page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="custom-page-link" aria-hidden="true"><</span>
                </li>
            @else
                <li class="custom-page-item">
                    <a class="custom-page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')"><</a>
                </li>
            @endif

            {{-- Các phần tử Pagination --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="custom-page-item disabled" aria-disabled="true"><span class="custom-page-link">{{ $element }}</span></li>
                @endif

                {{-- Mảng các Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="custom-page-item active" aria-current="page"><span class="custom-page-link">{{ $page }}</span></li>
                        @else
                            <li class="custom-page-item"><a class="custom-page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Nút Next --}}
            @if ($paginator->hasMorePages())
                <li class="custom-page-item">
                    <a class="custom-page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">></a>
                </li>
            @else
                <li class="custom-page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="custom-page-link" aria-hidden="true">></span>
                </li>
            @endif
        </ul>

        {{-- Tùy chọn: Hiển thị thông tin kết quả --}}
        {{-- <div class="pagination-results-info">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
        </div> --}}
    </nav>
@endif