<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LancamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lancamento')->insert([
            'data'          => Carbon::today(),
            'tipo'          => 'Receita',
            'categoria_id'  => 1,
            'descricao'     => 'Saldo Inicial',
            'valor'         => 0,
            'parcela'       => 1,
            'parcelaTotal'  => 1,
            'vencimento'    => Carbon::today(),
            'pagamento_id'  => 6,
            'credor_id'     => 1,
        ]);
    }
}
