<?php

namespace App\Http\Controllers;

use App\Models\Credor;
use Illuminate\Http\Request;

class CredorController extends Controller
{
    public function index(Request $request){

        $orderBy = $request->orderBy;
        $sortBy = $request->sortBy;
        $regPag = $request->regPag;

        if(!isset($orderBy)){$orderBy = 'id';}
        if(!isset($sortBy)){$sortBy = 'desc';}
        if(!isset($regPag)){$regPag = '30';}

        $datas = Credor::query()
        ->when(request('search') != null , function($query){
            return $query->where('credor.id', 'like', '%'.request('search').'%')
                ->orwhere('credor.nome', 'like', '%'.request('search').'%');
        })
        ->orderby($orderBy, $sortBy)
        ->paginate($regPag);

        return view('credor.table', compact('datas'));
    }

    public function merge(Request $request){
        if($request->id == null){
            Credor::create($request->all());
        } else {
            $categoria = Credor::query()->where('id','=',$request->id)->first();
            $categoria->update($request->all());
        }
        return $this->index($request);
    }

    public function view(Request $request){
        $data = Credor::query()->find($request->id);
        return response()->json($data);
    }

    public function destroy($id){
        Credor::destroy($id);
        return redirect()->route('credor');
    }
}
