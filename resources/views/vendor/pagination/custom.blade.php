@if ($paginator->hasPages())
    <nav class="d-flex justify-content-center my-2" aria-label="Navigasi Halaman">
        <ul class="pagination pagination-sm mb-0 flex-wrap gap-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link text-muted rounded px-2.5 py-1">Sebelumnya</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link text-dark rounded px-2.5 py-1" href="{{ $paginator->previousPageUrl() }}" rel="prev">Sebelumnya</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link rounded px-2.5 py-1">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link bg-success border-success text-white rounded px-2.5 py-1 fw-bold">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link text-dark rounded px-2.5 py-1" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link text-dark rounded px-2.5 py-1" href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link text-muted rounded px-2.5 py-1">Berikutnya</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
