<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Lancamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    private function obterPrimeiroDiaMesAtual(){
        return Carbon::now()->startOfMonth();
    }
    private function obterUltimoDiaMesAtual(){
        return Carbon::now()->endOfMonth();;
    }

    public function index(){

        // Dados para Chart Bar do mês atual
        $MesAtualLabel = ucfirst(Carbon::now()->locale('pt_BR')->isoFormat('MMMM/Y'));
        $primeiroDiaMesAtual = $this->obterPrimeiroDiaMesAtual();
        $ultimoDiaMesAtual = $this->obterUltimoDiaMesAtual();
        $MesAtual = Lancamento::query()
            ->whereBetween('vencimento', [$primeiroDiaMesAtual, $ultimoDiaMesAtual]);
        $receitaMesAtual = $MesAtual->where('valor', '>', 0)->sum('valor');
        $MesAtual = Lancamento::query()
            ->whereBetween('vencimento', [$primeiroDiaMesAtual, $ultimoDiaMesAtual]);
        $despesaMesAtual = $MesAtual->where('valor', '<', 0)->sum('valor')*-1;
    
        // Dados para Chart Bar do mês anterior
        $MesAnteriorLabel = ucfirst(Carbon::now()->subMonth()->locale('pt_BR')->isoFormat('MMMM/Y'));
        $primeiroDiaMesAnterior = Carbon::now()->subMonth()->startOfMonth();
        $ultimoDiaMesAnterior = Carbon::now()->subMonth()->endOfMonth();
        $MesAnterior = Lancamento::query()
            ->whereBetween('vencimento', [$primeiroDiaMesAnterior, $ultimoDiaMesAnterior]);
        $receitaMesAnterior = $MesAnterior->where('valor', '>', 0)->sum('valor');
        $MesAnterior = Lancamento::query()
            ->whereBetween('vencimento', [$primeiroDiaMesAnterior, $ultimoDiaMesAnterior]);
        $despesaMesAnterior = $MesAnterior->where('valor', '<', 0)->sum('valor')*-1;
    
        // Dados para Chart Bar do próximo mês
        $ProximoMesLabel = ucfirst(Carbon::now()->addMonth()->locale('pt_BR')->isoFormat('MMMM/Y'));
        $primeiroDiaProximoMes = Carbon::now()->addMonth()->startOfMonth();
        $ultimoDiaProximoMes = Carbon::now()->addMonth()->endOfMonth();
        $ProximoMes = Lancamento::query()
            ->whereBetween('vencimento', [$primeiroDiaProximoMes, $ultimoDiaProximoMes]);
        $receitaProximoMes = $ProximoMes->where('valor', '>', 0)->sum('valor');
        $ProximoMes = Lancamento::query()
            ->whereBetween('vencimento', [$primeiroDiaProximoMes, $ultimoDiaProximoMes]);
        $despesaProximoMes = $ProximoMes->where('valor', '<', 0)->sum('valor')*-1;

        // Dados para Chart Pie do mês atual
        $categoriaValor = Lancamento::leftJoin('categoria','categoria.id','=','lancamento.categoria_id')
            ->whereBetween('vencimento', [$primeiroDiaMesAtual, $ultimoDiaMesAtual])
            ->where('valor','<',0)
            ->groupBy('categoria.nome')
            ->selectRaw('sum(valor) as total, categoria.nome')
            ->orderBy('total', 'asc') // Ordena por total em ordem decrescente
            ->get();

            $categoriaValoresArray = [];
            $totalOutros = 0;

            // Percorra os resultados e construa o array associativo
            foreach ($categoriaValor as $categoria) {
                 if (count($categoriaValoresArray) < 5) {
                    $categoriaValoresArray[$categoria->nome] = $categoria->total;
                } else {
                    // Se já houver 5 categorias, adicione o valor a "Outros"
                    $totalOutros += $categoria->total;
                }
            }

            if (count($categoriaValor) > 5) {
                $categoriaValoresArray['Outros'] = $totalOutros;
            }
    
        $data = [
            'currentMonth' => [$receitaMesAtual, $despesaMesAtual], // Dados para o mês atual
            'previousMonth' => [$receitaMesAnterior, $despesaMesAnterior], // Dados para o mês anterior
            'nextMonth' => [$receitaProximoMes, $despesaProximoMes], // Dados para o próximo mês
            'salesData' => $categoriaValoresArray    
        ];
    
        return view('dashboard.index', compact('data','categoriaValor','MesAtualLabel','MesAnteriorLabel','ProximoMesLabel'));
    }

    public function getCategoria(Request $request){

        $primeiroDiaMesAtual = $this->obterPrimeiroDiaMesAtual();
        $ultimoDiaMesAtual = $this->obterUltimoDiaMesAtual();

        $data = Lancamento::leftJoin('categoria','categoria.id','=','lancamento.categoria_id')
        ->leftJoin('credor','credor.id','=','lancamento.credor_id')
        ->select('lancamento.*','categoria.nome AS categoria','credor.nome AS credor')
        ->addSelect(DB::raw('(lancamento.parcelaTotal - lancamento.parcela) AS parcelaDif'))
        ->where('lancamento.tipo','=','Despesa')
        ->where('categoria.nome','=',$request->categoria)
        ->whereBetween('vencimento', [$primeiroDiaMesAtual, $ultimoDiaMesAtual])
        ->orderBy('lancamento.vencimento')->orderBy('lancamento.descricao')
        ->orderBy('parcelaDif','desc')        
        ->get();
        return response()->json($data);
    }
}
