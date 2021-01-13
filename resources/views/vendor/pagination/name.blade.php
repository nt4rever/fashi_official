<ul class="pagination pagination-sm float-right">
    <div class="justify-content-center d-flex align-items-center">
        <p class="text-sm text-gray-700 leading-5 mr-3 mb-0">
            {!! __('Showing') !!}
            <span class="font-medium">{{ $paginator->firstItem() }}</span>
            {!! __('to') !!}
            <span class="font-medium">{{ $paginator->lastItem() }}</span>
            {!! __('of') !!}
            <span class="font-medium">{{ $paginator->total() }}</span>
            {!! __('results') !!}
        </p>
    </div>
    <!-- Previous Page Link -->
    @if ($paginator->onFirstPage()) 
    <li class="page-item"><a class="page-link disable" href="#">&laquo;</a></li>
    @else
    <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}">&laquo;</a></li>
    @endif

    <!-- Pagination Elements -->
    @foreach ($elements as $element)
    <!-- "Three Dots" Separator -->
    @if (is_string($element))
    <li class="page-item"><a class="page-link" href="#">{{ $element }}</a></li>
    @endif

    <!-- Array Of Links -->
    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if ($page == $paginator->currentPage())
    <li class="page-item"><a class="page-link disable" href="#">{{ $page }}</a></li>
    @else
    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
    @endif
    @endforeach
    @endif
    @endforeach

    <!-- Next Page Link -->
    @if ($paginator->hasMorePages())

    <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">&raquo;</a></li>
    @else
    <li class="page-item"><a class="page-link disable" href="#">&raquo;</i></a></li>
    @endif
    
</ul>
