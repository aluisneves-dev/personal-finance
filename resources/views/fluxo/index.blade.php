@extends('templateFin')

@section('content')

<form id="formFluxo" method="POST">
    @csrf
<div class="row my-3">
    <div class="col-auto">
        <label for="periodo" class="col-form-label">Período:</label>
    </div>
    <div class="col-auto">
        <select id="periodo" class="form-select" name="periodo">
            @foreach($periodos as $periodo)
                <option value="{{$periodo->numeroMes}}/{{$periodo->ano}}"
                    @if($periodo->numeroMes == $mes && $periodo->ano == $ano)
                        selected
                    @endif
                >
                    {{$periodo->mes}}/{{$periodo->ano}}
                </option>
            @endforeach
        </select>
    </div>
</div>
</form>

<div class="table-responsive">
    <table id="mytable" class="table table-hover">
        <thead>
            <tr>
                <th class="table-primary" colspan="10">
                    <div class="container text-center">
                        <div class="row">
                            <div class="d-flex justify-content-between">
                              <div class="d-flex align-items-center">
                                <h4>Fluxo Financeiro&nbsp;<i class="fas fa-comment-dollar"></i></h4>
                              </div>
                              <div class="text-end fw-normal">
                                  <strong>Saldo Anterior (R$):</strong>&nbsp;<span class="{{ $saldoAcumulado < 0 ? 'text-danger' : '' }}">{{number_format($saldoAcumulado, 2, ',', '.') }}</br>
                                  <strong>Receita (R$):</strong>&nbsp;<span class="{{ $receitaMesAtual < 0 ? 'text-danger' : '' }}">{{number_format($receitaMesAtual, 2, ',', '.') }}</br>
                                  <strong>Despesa (R$):</strong>&nbsp;<span class="{{ $despesaMesAtual < 0 ? 'text-danger' : '' }}">{{number_format($despesaMesAtual, 2, ',', '.') }}</br></span>
                                  <strong>Saldo Atual (R$):</strong>&nbsp;<span class="{{ $saldoMes < 0 ? 'text-danger' : '' }}">{{number_format($saldoMes, 2, ',', '.') }}</span> 
                              </div>
                          </div>
                      </div>
                    </div>
                </th>
            </tr>
            <tr class="table-light">
                <th scope="col"></th>
                <th scope="col">Vencimento</th>
                <th scope="col">Tipo</th>
                <th scope="col">Categoria</th>
                <th scope="col">Descrição</th>
                <th scope="col">Origem/Destino</th>
                <th scope="col">Valor (R$)</th>
                <th scope="col">Saldo (R$)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datas as $data)
            <tr>
                <td class="text-center">
                    <button id="btn-editar-fluxo" class="btn btn-primary btn-sm" data-vencimento="{{$data->vencimento}}" data-valor="{{$data->valorTotal}}" data-categoria="{{$data->categoriaNome}}" data-tipo="{{$data->tipo}}">
                        <i class="fa-solid fa-pen-to-square fa-sm"></i>
                    </button>
                </td>
                <td id="vencimento">{{$data->vencimento}}</td>
                <td>{{$data->tipo}}</td>
                <td>{{$data->categoriaNome}}</td>
                <td>{{$data->descricaoNome}}</td>
                <td>{{$data->credorNome}}</td>
                <td class="text-end">{{number_format($data->valorTotal, 2, ',','.')}}</td>
                <td class="text-end">{{number_format($saldoAcumulado += floatval($data->valorTotal), 2, ',', '.') }}</td>
            @endforeach
            </tr>
        </tbody>
    </table>
</div>
@include('pagination')

<!-- Modal CREATE -->
<div class="modal fade" id="modal_lancamentos" tabindex="-1" aria-labelledby="modal_lancamentos" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title fs-5" id="modal_lancamentos">Lançamento</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formModal">
      @csrf
          <div class="row g-3">
              <input id="id" name="id" type="hidden">
            <div class="col-md-2">
              <label for="data" class="form-label">Data:</label>
              <input id="data" name="data" class="form-control maskDate" type="text" placeholder="Data" value="<?php echo date("d/m/Y"); ?>" required>
            </div>
            <div class="col-md-4">
              <label for="tipo" class="form-label">Tipo:</label>
              <select id="tipo" name="tipo" class="form-select" required>
                <option>Selecione</option>
                <option>Receita</option>
                <option>Despesa</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="categoria" class="form-label">Categoria:</label>
              <select id="categoria" name="categoria_id" class="form-select" required>
                <option></option>
              </select>
            </div>
            <div class="col-md-7">
              <label for="descricao"class="form-label">Descrição:</label>
              <input id="descricao" name="descricao" class="form-control" type="text" placeholder="Descrição" required>
            </div>
            <div class="col-md-3">
              <label for="valor"class="form-label">Valor (R$):</label>
              <input id="valor" name="valor" class="form-control" type="text" placeholder="Valor" required>
            </div>
            <div class="col-md-1">
              <label for="parcela"class="form-label">Parcelas:</label>
              <input id="parcela" name="parcela" class="form-control" type="text" value="1" style="text-align:center;" required>
            </div>
            <div class="col-md-1">
              <label for="parcelaTotal"class="form-label">&nbsp;</label>
              <input id="parcelaTotal" name="parcelaTotal" class="form-control" type="text" value="1" style="text-align:center;" required>
            </div>
            <div class="col-md-2">
              <label for="vencimento" class="form-label">Vencimento:</label>
              <input id="vencimento" name="vencimento" class="form-control maskDate" type="text" placeholder="Vencimento" value="<?php echo date("d/m/Y"); ?>" required>
            </div>
            <div class="col-md-4">
              <label for="pagamento" class="form-label">Meio de Pagamento:</label>
              <select id="pagamento" name="pagamento_id" class="form-select" required>
                <option value="" selected>Selecionar</option>
                @foreach($pagamentos as $pagamento)
                  <option value="{{$pagamento->id}}">{{$pagamento->nome}}</option>
                @endforeach
              </select>
            </div>
  
            <div class="col-md-6">
              <label for="credor" class="form-label">Credor:</label>
              <select id="credor" name="credor_id" class="form-select" required>
                <option value="" selected>Selecionar</option>
                @foreach($credores as $credor)
                  <option value="{{$credor->id}}">{{$credor->nome}}</option>
                @endforeach
              </select>
            </div>
  
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i>&nbsp;Fechar</button>
          <button type="button" class="btn btn-sm btn-success" id="btnFormFluxoLancamento"><i class="fa-regular fa-floppy-disk"></i>&nbsp;Salvar</button>
        </div>
      </form>
      </div>
    </div>
  </div>
  @endsection