<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index(Request $request){

        $orderBy = $request->orderBy;
        $sortBy = $request->sortBy;
        $regPag = $request->regPag;

        if(!isset($orderBy)){$orderBy = 'id';}
        if(!isset($sortBy)){$sortBy = 'desc';}
        if(!isset($regPag)){$regPag = '20';}

        $datas = Categoria::query()
        ->when(request('search') != null , function($query){
            return $query->where('categoria.id', 'like', '%'.request('search').'%')
                ->orwhere('categoria.tipo', 'like', '%'.request('search').'%')
                ->orwhere('categoria.nome', 'like', '%'.request('search').'%');
        })
        ->orderby($orderBy, $sortBy)->orderby('tipo')->orderby('nome')
        ->paginate($regPag);

        return view('categoria.table', compact('datas'));
    }

    public function merge(Request $request){
        if($request->id == null){
            Categoria::create($request->all());
        } else {
            $categoria = Categoria::query()->where('id','=',$request->id)->first();
            $categoria->update($request->all());
        }
        return $this->index($request);
    }

    public function view(Request $request){

        $data = Categoria::query()->find($request->id);
        return response()->json($data);
    }

    public function destroy($id){
        Categoria::destroy($id);
        return redirect()->route('categoria');
    }
    
    public function getCategoria(Request $request){
        $categorias = Categoria::query()->where('tipo','=',$request->tipo)->orderBy('nome')->get();
        foreach ($categorias as $categoria) {
            echo "<option value='$categoria->id'>$categoria->nome</option>";
        }
    }
}