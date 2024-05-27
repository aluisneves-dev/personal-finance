<?php

namespace App\Http\Controllers;

use App\Models\Lancamento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartaoController extends Controller
{
    public function index(Request $request){

        $orderBy = $request->orderBy;
        $sortBy = $request->sortBy;
        $regPag = $request->regPag;

        if(!isset($orderBy)){$orderBy = 'vencimento';}
        if(!isset($sortBy)){$sortBy = 'asc';}
        if(!isset($regPag)){$regPag = '20';}

        $datas = Lancamento::leftJoin('pagamento','pagamento.id','=','lancamento.pagamento_id')
        ->where('pagamento.nome','LIKE','%Cartão de Crédito%')
        ->select(['pagamento.nome AS cartao','lancamento.vencimento',
            DB::raw('SUM(lancamento.valor) AS valorTotal')])
        ->where('lancamento.vencimento','>',Carbon::now()->subMonth())
        ->groupBy(['cartao','lancamento.vencimento'])
        ->orderBy($orderBy, $sortBy)->orderBy('lancamento.vencimento')->orderBy('cartao')
        ->get();

        $vencimentos = $datas->pluck('vencimento')->unique();

        return view('cartao.table', compact('datas','vencimentos'));
    }

    public function getCartao(Request $request){

        $vencimento = Carbon::createFromFormat('d/m/Y', $request->vencimento)->format('Y-m-d');
        $pagamento = $request->pagamento;
        $data = Lancamento::leftJoin('pagamento','pagamento.id','=','lancamento.pagamento_id')
        ->leftJoin('categoria','categoria.id','=','lancamento.categoria_id')
        ->leftJoin('credor','credor.id','=','lancamento.credor_id')
        ->select(['lancamento.*','categoria.nome AS categoria','credor.nome AS credor'])
        ->addSelect(DB::raw('(lancamento.parcelaTotal - lancamento.parcela) AS parcelaDif'))
        ->where('lancamento.vencimento','=',$vencimento)
        ->where('pagamento.nome','=', $pagamento)
        ->orderBy('lancamento.data', 'asc')->orderBy('categoria')->orderBy('lancamento.descricao')->orderBy('parcelaDif','desc')
        ->get();
        return response()->json($data);
    }
}
