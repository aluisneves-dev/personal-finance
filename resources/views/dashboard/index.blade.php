@extends('templateFin')

@section('content')
<div class="mt-4">
    <h4>Dashboard</h4>

    <div class="mt-4" style="display: flex; justify-content: space-around;">

        <div class="card card-header mt-4">
            <canvas id="salesChart" width="300" height="300"></canvas>
        </div>
        
        <div class="card card-header table-responsive my-3">
            <table class="table table-hover">
                <thead>
                    <tr class="table-light">
                        <th scope="col">Categoria</th>
                        <th scope="col">Valor (R$)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalGeral = 0;
                    @endphp
                    @foreach($categoriaValor as $categoriaValorData)
                    <tr>
                        <td><a href="#" id="linkDashboardCategoria" class="text-decoration-none text-dark" data-categoria="{{$categoriaValorData->nome}}">{{$categoriaValorData->nome}}</a></td>
                        <td class="text-end">{{number_format($categoriaValorData->total, 2, ',','.')}}</td>            
                    </tr>
                    @php
                        $totalGeral += $categoriaValorData->total;
                    @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total Geral</th>
                        <th class="text-end">{{number_format($totalGeral, 2, ',','.')}}</th>
                    </tr>
                </tfoot>
                
            </table>
        </div>
    </div>

    <div class="mt-4" style="display: flex; justify-content: space-around;">
        <div class="card card-header">
            <canvas id="previousMonthChart" width="400" height="200"></canvas>
        </div>
        <div class="card card-header">
            <canvas id="currentMonthChart" width="400" height="200"></canvas>
        </div>
        <div class="card card-header">
            <canvas id="nextMonthChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>

<div class="modal fade" id="dashBoardModal" tabindex="-1" aria-labelledby="dashBoardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title fs-5" id="dashBoardModalLabel"></h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Vencimento</th>
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

<script type="text/javascript">
    var ctx1 = document.getElementById('previousMonthChart').getContext('2d');
    var ctx2 = document.getElementById('currentMonthChart').getContext('2d');
    var ctx3 = document.getElementById('nextMonthChart').getContext('2d');
  
    var previousMonthData = @json($data['previousMonth']);
    var currentMonthData = @json($data['currentMonth']);
    var nextMonthData = @json($data['nextMonth']);

    var currentMonthDataLabel ="{{ $MesAtualLabel }}";
    var previousMonthDataLabel ="{{ $MesAnteriorLabel }}";
    var nextMonthDataLabel ="{{ $ProximoMesLabel }}";

    var options = {
        scales: {
            y: {
                min: 0,
                max: 25000,
                ticks: {
                    stepSize: 5000,
                }
            }
        }
    };

    var previousMonthChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Receita', 'Despesa'],
            datasets: [{
                label: previousMonthDataLabel,
                data: previousMonthData,
                backgroundColor: ['blue', 'red'],
                borderColor: ['blue', 'red'],
                borderWidth: 1
            }],
        },
        options: options,
    });

    var currentMonthChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Receita', 'Despesa'],
            datasets: [{
                label: currentMonthDataLabel,
                data: currentMonthData,
                backgroundColor: ['blue', 'red'],
                borderColor: ['blue', 'red'],
                borderWidth: 1
            }],
        },
        options: options,
    });

    var nextMonthChart = new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: ['Receita', 'Despesa'],
            datasets: [{
                label: nextMonthDataLabel,
                data: nextMonthData,
                backgroundColor: ['blue', 'red'],
                borderColor: ['blue', 'red'],
                borderWidth: 1
            }],
        },
        options: options,
    });

    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesData = @json($data['salesData']);
    var salesChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(salesData),
            datasets: [{
                label: 'Valor (R$)',
                data: Object.values(salesData),
                backgroundColor: [
                    'lightsteelblue',
                    'lightcoral',
                    'lightgoldenrodyellow',
                    'lightblue',
                    'lightgray',
                    'lightgreen',
                ],
            }],
        },
        options: {
            aspectRatio: 1, // Proporção da largura em relação à altura (1 = tamanho quadrado)
            maintainAspectRatio: false, // Desabilita a manutenção automática da proporção
            legend: {
                display: true,
                position: 'chartArea', // You can change the position of the legend
                labels: {
                    fontColor: 'black' // You can change the font color of the legend labels
                }
            }  
        },
    });
</script>
@endsection
