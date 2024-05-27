<div class="table-responsive">
  <table class="table table-hover">
      <thead>
        <tr>
          <th class="table-primary" colspan="4">
            <h4>Tabela Categoria</h4>
        </tr>
        <tr class="table-light">
          <th scope="col">#</th>
          <th scope="col">Tipo</th>
          <th scope="col">Categoria</th>
          <th scope="col">Opções</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($datas as $data)
        <tr>
          <th scope="row">{{$data->id}}</th>
          <td>{{$data->tipo}}</td>
          <td>{{$data->nome}}</td>
          <td>
            <button id="btn-edit-categoria" type="button" class="btn btn-dark btn-sm" data-id="{{$data->id}}">
              <i class="fa-solid fa-pen-to-square"></i>
            </button>
            <a href="/categoria/delete/{{$data->id}}"><button type="button" class="btn btn-danger btn-sm">
              <i class="fa-solid fa-trash"></i>
            </button>
          </td>
        @endforeach
        </tr>
      </tbody>
  </table>
</div>
@include('pagination')

<!-- Modal CREATE -->
<div class="modal fade" id="modal_categoria" tabindex="-1" aria-labelledby="modal_categoria" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modal_categoria">Categoria</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form id="formModal">
      @csrf
        <input type="hidden" name="id" id="id">
        <div class="mb-3">
          <label for="categoria_tipo" class="form-label">Tipo</label>
          <select name="tipo" class="form-select" id="categoria_tipo" required>
            <option value="" selected>Selecionar</option>
            <option value="Receita">Receita</option>
            <option value="Despesa">Despesa</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="categoria_nome" class="form-label">Categoria</label>
          <input name="nome" type="text" class="form-control" id="categoria_nome" required>
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