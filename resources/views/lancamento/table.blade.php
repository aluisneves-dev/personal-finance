<div class="table-responsive">
  <table class="table table-hover">
      <thead>
        <tr>
          <th class="table-primary" colspan="12">
            <h4>Lançamentos&nbsp;<i class="fas fa-comment-dollar"></i></h4>
        </tr>
        <tr class="table-light">
          <th scope="col" class="text-center">#</th>
          <th scope="col" class="text-center">Data</th>
          <th scope="col" class="text-center">Tipo</th>
          <th scope="col">Categoria</th>
          <th scope="col">Descrição</th>
          <th scope="col" class="text-center">Valor (R$)</th>
          <th scope="col" class="text-center">Vencimento</th>
          <th scope="col">Meio Pagamento</th>
          <th scope="col" class="text-center">Parcelas</th>
          <th scope="col">Credor</th>
          <th scope="col">Opções</th>
        </tr>
      </thead>
      <tbody>
        @if(isset($datas) && $datas->count() > 0)
        @foreach ($datas as $data)
        <tr>
          <th scope="row" class="text-center">{{$data->id}}</th>
          <td class="text-center">{{$data->data}}</td>
          <td class="text-center">{{$data->tipo}}</td>
          <td>{{$data->categoria->nome}}</td>
          <td>{{$data->descricao}}</td>
          <td class="text-center {{ $data->tipo === 'Despesa' ? 'text-danger' : '' }}">{{ $data->valor }}</td>
          <td class="text-center">{{$data->vencimento}}</td>
          <td>{{$data->pagamento->nome}}</td>
          <td class="text-center">{{$data->parcela}} de {{$data->parcelaTotal}}
          <td>{{$data->credor->nome}}</td>
          <td>
            <button id="btn-edit-lancamento" class="btn btn-primary btn-sm" data-id="{{$data->id}}" data-tipo="{{$data->tipo}}">
              <i class="fa-solid fa-pen-to-square fa-sm"></i>
            </button>
            <button id="btn-more-lancamento" class="btn btn-dark btn-sm" data-id="{{$data->id}}" data-tipo="{{$data->tipo}}">
              <i class="far fa-clone"></i>
            </button>
            <button id="btn-delete-lancamento" class="btn btn-danger btn-sm" data-id="{{$data->id}}">
              <i class="fa-solid fa-trash fa-sm"></i>
            </button>
          </td>
        @endforeach
        @endif
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
        <button type="button" class="btn btn-sm btn-success" id="btn-form"><i class="fa-regular fa-floppy-disk"></i>&nbsp;Salvar</button>
      </div>
    </form>
    </div>
  </div>
</div>