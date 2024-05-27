<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Credor;
use App\Models\Pagamento;
use App\Models\Lancamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FluxoController extends Controller
{

    public function index(Request $request){
        DB::statement("SET lc_time_names = 'pt_BR'");

        $credores = Credor::query()->orderby('nome')->get();
        $pagamentos = Pagamento::query()->orderby('nome')->get();
        
        $periodos = Lancamento::select(
            DB::raw('YEAR(vencimento) as ano'),
            DB::raw('MONTH(vencimento) as numeroMes'),
            DB::raw("CONCAT(UCASE(LEFT(MONTHNAME(vencimento), 1)), LCASE(SUBSTRING(MONTHNAME(vencimento), 2))) as mes"),
            DB::raw('(SELECT MIN(vencimento) FROM lancamento WHERE YEAR(vencimento) = ano AND MONTH(vencimento) = numeroMes) as primeiraData')
        )
            ->groupBy('ano', 'numeroMes', 'mes')
            ->orderBy('primeiraData')
            ->get();

        if($request->periodo === null){
            $ano = date('Y');
            $mes = date('m');
        } else {
            $data_parts = explode('/', $request->periodo);
            $mes = $data_parts[0];
            $ano = $data_parts[1];
        }
        $mesExtenso = ucfirst(Carbon::create()->month($mes)->monthName);

        $datas = Lancamento::leftJoin('categoria','categoria.id','=','lancamento.categoria_id')
        ->leftJoin('credor','credor.id','=','lancamento.credor_id')
        ->leftJoin('pagamento','pagamento.id','=','lancamento.pagamento_id')
        ->select([
            DB::raw('YEAR(vencimento) as ano'),
            DB::raw('CONCAT(UCASE(LEFT(MONTHNAME(vencimento), 1)), LCASE(SUBSTRING(MONTHNAME(vencimento), 2))) as mes'),
            DB::raw('MONTH(vencimento) as numeroMes'),
            'lancamento.vencimento',
            'lancamento.tipo',
            DB::raw('CASE WHEN pagamento.nome LIKE "%Cartão de Crédito%" THEN "Cartão de Crédito" ELSE categoria.nome END AS categoriaNome'),
            DB::raw('CASE WHEN pagamento.nome LIKE "%Cartão de Crédito%" THEN "Cartão de Crédito" ELSE lancamento.descricao END AS descricaoNome'),
            DB::raw('CASE WHEN pagamento.nome LIKE "%Cartão de Crédito%" THEN pagamento.nome ELSE credor.nome END AS credorNome'),
            DB::raw('SUM(lancamento.valor) AS valorTotal')])
        ->groupBy(['ano','mes','numeroMes','lancamento.vencimento','lancamento.tipo','pagamento.nome','categoriaNome','descricaoNome','credorNome'])
        ->whereRaw('YEAR(lancamento.vencimento) = ?', $ano)
        ->whereRaw('MONTH(lancamento.vencimento) = ?', $mes)
        ->orderBy('vencimento')->orderBy('lancamento.tipo','desc')->orderBy('categoriaNome','desc')
        ->paginate(100);

        $mesSelecionado = $mes;
        $anoSelecionado = $ano;
        $mesAnterior = $mesSelecionado - 1;
        $anoAnterior = $ano;
        if($mesAnterior == 0){
            $mesAnterior = 12;
            $anoAnterior = $ano - 1;
        }
        $dataParametro = Carbon::create($ano, $mes, 1)->startOfMonth();
        $saldoAcumulado = Lancamento::query()->whereDate('vencimento', '<', $dataParametro)->sum('valor');
        $primeiroDiaMesAtual = Carbon::create($ano, $mes, 1)->startOfMonth();
        $ultimoDiaMesAtual = Carbon::create($ano, $mes, 1)->endOfMonth();
        $dataMesAtual = Lancamento::query()
            ->whereBetween('vencimento', [$primeiroDiaMesAtual, $ultimoDiaMesAtual]);
        $receitaMesAtual = $dataMesAtual->where('valor', '>', 0)->sum('valor');
        $dataMesAtual = Lancamento::query()
            ->whereBetween('vencimento', [$primeiroDiaMesAtual, $ultimoDiaMesAtual]);
        $despesaMesAtual = $dataMesAtual->where('valor', '<', 0)->sum('valor');
        $saldoMes = $receitaMesAtual + $despesaMesAtual;

        return view('fluxo.index', compact('credores','pagamentos','periodos','datas','mes','ano','mesExtenso','mesSelecionado','anoSelecionado','mesAnterior','anoAnterior','saldoAcumulado','receitaMesAtual','despesaMesAtual','saldoMes'));
    }

    public function getLancamento(Request $request){

        $vencimentos = collect($request->vencimento)->map(function ($date) {
            return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        });
        $valor = $request->valor;
        $data = Lancamento::query()
        ->where('vencimento','=',$vencimentos)
        ->where('valor','=', $valor)
        ->first();
        return response()->json($data);
    }
}
