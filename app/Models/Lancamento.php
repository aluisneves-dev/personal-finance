<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lancamento extends Model
{
    use HasFactory;

    protected $table = 'lancamento';
    protected $fillable = [
        'data',
        'tipo',
        'categoria_id',
        'descricao',
        'valor',
        'parcela',
        'parcelaTotal',
        'vencimento',
        'pagamento_id',
        'credor_id',
    ];

    protected function getDataAttribute($value){
        return strlen($value) ? Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y') : null;
    }

    protected function setDataAttribute($value){
        $this->attributes['data'] = strlen($value) ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
    }

    protected function getVencimentoAttribute($value){
        return strlen($value) ? Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y') : null;
    }

    protected function setVencimentoAttribute($value){
        $this->attributes['vencimento'] = strlen($value) ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
    }

    protected function setValorAttribute($value){
        $this->attributes['valor'] = floatval(str_replace(array('.',','), array('','.'), $value));
    }

    protected function getValorAttribute($value){
        return number_format($value, 2, ',', '.');
    }

    public function pagamento(){
        return $this->belongsTo(Pagamento::class,'pagamento_id');
    }

    public function credor(){
        return $this->belongsTo(Credor::class,'credor_id');
    }

    public function categoria(){
        return $this->belongsTo(Categoria::class,'categoria_id');
    }

    public function getPeriodoAttribute(){
        return Carbon::parse($this->attributes['vencimento'])->translatedFormat('F/Y');
    }

}