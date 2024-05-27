<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use Illuminate\Http\Request;

class PagamentoController extends Controller
{
    public function index(Request $request){

        $orderBy = $request->orderBy;
        $sortBy = $request->sortBy;
        $regPag = $request->regPag;

        if(!isset($orderBy)){$orderBy = 'id';}
        if(!isset($sortBy)){$sortBy = 'desc';}
        if(!isset($regPag)){$regPag = '20';}

        $datas = Pagamento::query()
        ->when(request('search') != null , function($query){
            return $query->where('pagamento.id', 'like', '%'.request('search').'%')
                ->orwhere('pagamento.nome', 'like', '%'.request('search').'%');
        })
        ->orderby($orderBy, $sortBy)
        ->paginate($regPag);
        return view('pagamento.table', compact('datas'));
    }

    public function merge(Request $request){
        if($request->id == null){
            Pagamento::create($request->all());
        } else {
            $categoria = Pagamento::query()->where('id','=',$request->id)->first();
            $categoria->update($request->all());
        }
        return $this->index($request);
    }

    public function view(Request $request){
        $data = Pagamento::query()->find($request->id);
        return response()->json($data);
    }

    public function destroy($id){
        Pagamento::destroy($id);
        return redirect()->route('pagamento');
    }
}
