<div>
    @if(isset($datas))
        @if($datas->count() < 1)
            <p class="text-center mt-3">Nenhum registro encontrado</p>
        @else
            <div class="text-center">
                <small>Exibindo {{$datas->firstItem()}} a {{$datas->lastItem()}} do total de {{$datas->total()}} registros</small>
            </div>
            <div class="d-flex justify-content-center">
                {{ $datas->links('pagination::bootstrap-4') }}
            </div>
        @endif
    @endif
</div>