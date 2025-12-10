<!-- Custom pagination layout -->
<style>
    .page-link {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    .page-item {
        margin:2px;
    }
</style>
@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-end">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link rounded-circle">&lt;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link rounded-circle" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lt;</a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled">
                        <span class="page-link rounded-circle">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link rounded-circle bg-primary text-white border-0">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link rounded-circle" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link rounded-circle" href="{{ $paginator->nextPageUrl() }}" rel="next">&gt;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link rounded-circle">&gt;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif