@if ($paginator->hasPages())
<nav class="pager" aria-label="Paginación">
    <span>Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }} · {{ $paginator->total() }} registros</span>
    <div class="pager-buttons">
        @if ($paginator->onFirstPage())
            <span>Anterior</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev">Anterior</a>
        @endif
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next">Siguiente</a>
        @else
            <span>Siguiente</span>
        @endif
    </div>
</nav>
@endif
