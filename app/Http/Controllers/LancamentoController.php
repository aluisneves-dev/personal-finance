<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Credor;
use App\Models\Pagamento;
use App\Models\Lancamento;
use Illuminate\Http\Request;

class LancamentoController extends Controller
{
    public function index(Request $request){

        $orderBy = $request->orderBy;
        $sortBy = $request->sortBy;
        $regPag = $request->regPag;

        if(!isset($orderBy)){$orderBy = 'id';}
        if(!isset($sortBy)){$sortBy = 'desc';}
        if(!isset($regPag)){$regPag = '20';}

        $hojeMenosDoisMeses = Carbon::now()->subMonths(2);

        $datas = Lancamento::leftJoin('categoria','categoria.id','=','lancamento.categoria_id')
        ->leftJoin('pagamento','pagamento.id','=','lancamento.pagamento_id')
        ->leftJoin('credor','credor.id','=','lancamento.credor_id')
        ->select('lancamento.*')
        ->where('lancamento.vencimento','>', $hojeMenosDoisMeses)
        ->when(request('search') != null , function($query) use ($hojeMenosDoisMeses){
            return $query->where('lancamento.vencimento', '>', $hojeMenosDoisMeses)
            ->where(function ($query) {
            return $query->where('lancamento.id', 'like', '%'.request('search').'%')
            ->orwhere('lancamento.tipo', 'like', '%'.request('search').'%')
            ->orwhere('categoria.nome', 'like', '%'.request('search').'%')
            ->orwhere('lancamento.descricao', 'like', '%'.request('search').'%')
            ->orwhere('lancamento.valor', 'like', '%'.request('search').'%')
            ->orwhere('pagamento.nome', 'like', '%'.request('search').'%')
            ->orwhere('credor.nome', 'like', '%'.request('search').'%');
            });
        })
        ->orderby($orderBy, $sortBy)->orderBy('tipo')->paginate($regPag);
        $credores = Credor::query()->orderby('nome')->get();
        $pagamentos = Pagamento::query()->orderby('nome')->get();

        return view('lancamento.table', compact('datas','credores','pagamentos'));
    }

    public function view(Request $request){

        $data = Lancamento::query()->find($request->id);
        if($data->tipo === "Despesa"){
            $valorFormatado = str_replace(['.', ','], ['', '.'], $data->valor);
            $valorNumerico = floatval($valorFormatado);
            $valorPositivo = ($valorNumerico * -1);
            $valorGravar = str_replace([',', '.'], ['.', ','], $valorPositivo);
        }

        if($data->tipo === "Despesa"){
            $data['valor'] = $valorGravar;
        }

        return response()->json($data);
    }

    public function merge(Request $request){

        if($request->tipo === "Despesa"){
            $valorFormatado = str_replace(['.', ','], ['', '.'], $request->valor);
            $valorNumerico = floatval($valorFormatado);
            $valorNegativo = -$valorNumerico;
            $valorGravar = str_replace([',', '.'], ['.', ','], $valorNegativo);
        }

        $requestData = $request->all();

        if($request->tipo === "Despesa"){
            $requestData['valor'] = $valorGravar;
        }

        if($requestData['id'] == null){
            Lancamento::create($requestData);
        } else {
            $lancamento = Lancamento::query()->where('id','=',$requestData['id'])->first();
            $lancamento->update($requestData);
        }        
        
        return $this->index($request);
    }

    public function mergeFluxo(Request $request){

        if($request->tipo === "Despesa"){
            $valorFormatado = str_replace(['.', ','], ['', '.'], $request->valor);
            $valorNumerico = floatval($valorFormatado);
            $valorNegativo = -$valorNumerico;
            $valorGravar = str_replace([',', '.'], ['.', ','], $valorNegativo);
        }

        $requestData = $request->all();

        if($request->tipo === "Despesa"){
            $requestData['valor'] = $valorGravar;
        }

        if($requestData['id'] == null){
            Lancamento::create($requestData);
        } else {
            $lancamento = Lancamento::query()->where('id','=',$requestData['id'])->first();
            $lancamento->update($requestData);
        }
        
        return response()->json('success');
    }

    public function clone($id){
        $data = Lancamento::query()->where('id','=',$id)->first();
        $parcelaSaldo = ( $data->parcelaTotal - $data->parcela );
        
        if($parcelaSaldo > 0){

            $data->parcela = ($data->parcela + 1);

            $data_parts = explode('/', $data->vencimento);
                $dia = $data_parts[0];
                $mes = $data_parts[1];
                $ano = $data_parts[2];
        
            $novaData = Carbon::create($ano, $mes, $dia, 0, 0, 0)->addMonth();

            $data->vencimento = $novaData->format('d/m/Y');
        }

        Lancamento::create($data->toArray());

        return response()->json('success');
    }

    public function destroy($id){
        Lancamento::destroy($id);
        return response()->json('success');
    }
}