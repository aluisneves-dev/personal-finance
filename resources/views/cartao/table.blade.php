<div class="table-responsive my-3">
    <table id="mytable" class="table table-hover">
        <thead>
            <tr>
                <th class="table-primary" colspan="4">
                    <div class="container text-center">
                        <div class="row">
                            <div class="col d-flex align-items-center">
                                <h4>Cartão de Crédito&nbsp;<i class="fas fa-comment-dollar"></i></h4>
                            </div>
                        </div>
                    </div>
                </th>
            </tr>
            <tr class="table-light">
                <th scope="col" class="text-center">View</th>
                <th scope="col">Cartão de Crédito</th>
                <th scope="col">Vencimento</th>
                <th scope="col">Valor (R$)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datas as $data)
            <tr>
                <td class="text-center">
                    <button id="btn-view-cartao" class="btn" data-vencimento="{{$data->vencimento}}" data-pagamento="{{$data->cartao}}">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </td>
                <td>
                    @if($data->cartao == 'Cartão de Crédito Visa')
                        <i class="fab fa-cc-visa"></i>
                    @else
                        <i class="fab fa-cc-mastercard"></i>
                    @endif
                    {{$data->cartao}}
                </td>
                <td>{{$data->vencimento}}</td>
                <td>{{number_format($data->valorTotal, 2, ',','.')}}</td>  
            @endforeach
            </tr>
        </tbody>
    </table>
</div>

<div class="modal fade" id="cartaoModal" tabindex="-1" aria-labelledby="cartaoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title fs-5" id="cartaoModalLabel"></h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Categoria</th>
                            <th>Descrição</th>
                            <th>Valor (R$)</th>
                            <th>Credor</th>
                            <th class="text-center">Parcelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
</div>