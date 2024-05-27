@extends('templateFin')

@section('content')

<form class="form" id="formTable">
<div class="row my-3">
    <div class="col-auto">
        <label class="col-form-label">Ordem:</label>
    </div>
    <div class="col-auto">
        <select name="orderBy" id="orderBy" class="form-select">
          <option value="cartao">Cartão de Crédito</option>
          <option value="vencimento" selected>Vencimento</option>
          <option value="valorTotal">Valor</option>
        </select>
    </div>
    <div class="col-auto">
        <label class="col-form-label">Class:</label>
      </div>
      <div class="col-auto">
        <select name="sortBy" id="sortBy" class="form-select">
            <option value="" selected>Selecionar</option>
            <option value="asc">A - Z</option>
            <option value="desc">Z - A</option>
        </select>
      </div>
    <div class="col-auto">
        <label for="vencimento" class="col-form-label">Vencimento:</label>
    </div>
    <div class="col-auto">
        <select id="vencimento" class="form-select" name="vencimento">
          <option>Selecionar</option>
        </select>
    </div>
</div>
</form>

<div class="card">
  <div class="card-header">
</div>
@endsection