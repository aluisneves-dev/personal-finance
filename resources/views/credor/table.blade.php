<div class="table-responsive">
  <table class="table table-hover">
      <thead>
        <tr>
          <th class="table-primary" colspan="4">
            <h4>Tabela Credor</h4>
        </tr>
        <tr class="table-light">
          <th scope="col">#</th>
          <th scope="col">Credor</th>
          <th scope="col">Opções</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($datas as $data)
        <tr>
          <th scope="row">{{$data->id}}</th>
          <td>{{$data->nome}}</td>
          <td>
            <button id="btn-edit-credor" type="button" class="btn btn-dark btn-sm" data-id="{{$data->id}}">
              <i class="fa-solid fa-pen-to-square"></i>
            </button>
            <a href="/credor/delete/{{$data->id}}"><button type="button" class="btn btn-danger btn-sm">
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
<div class="modal fade" id="modal_credor" tabindex="-1" aria-labelledby="modal_credor" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modal_credor">Credor</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form id="formModal">
      @csrf
      <input type="hidden" name="id" id="id">
        <div class="mb-3">
          <label for="credor_nome" class="form-label">Credor</label>
          <input name="nome" type="text" class="form-control" id="credor_nome" required>
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