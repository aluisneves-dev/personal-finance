@extends('templateFin')

@section('content')

<form class="form" id="formTable">
@csrf

<div class="row my-3">
  <div class="col-auto">
    <label class="col-form-label">Ordem:</label>
  </div>
  <div class="col-auto">
    <select name="orderBy" id="orderBy" class="form-select">
      <option value="" selected>Selecionar</option>
      <option value="id">ID</option>
      <option value="nome">Forma de Pagamento</option>
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
    <label class="col-form-label">Exibir:</label>
  </div>
  <div class="col-auto">
    <select name="regPag" id="regPag" class="form-select">
      <option value="20" selected>20 registros</option>
      <option value="50">50 registros</option>
      <option value="100">100 registros</option>
    </select>
  </div>
  <div class="col-md-3">
    <input class="form-control" type="text" id="search" name="search" placeholder="Pesquisa livre">
  </div>
  <div class="col-auto">
    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modal_pagamento">
      <i class="fa-solid fa-plus"></i>&nbsp;Novo
    </button>
  </div>
  <input type="hidden" id="page" name="page" value="0">
</div>
</form>

<div class="card">
  <div class="card-header">
</div>
@endsection