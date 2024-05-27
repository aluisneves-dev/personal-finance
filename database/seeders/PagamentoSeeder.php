<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pagamento')->insert([
            'id'    => '1',
            'nome'  => 'Cartão de Crédito Mastercard',
        ]);

        DB::table('pagamento')->insert([
            'id'    => '2',
            'nome'  => 'Cartão de Crédito Visa',
        ]);

        DB::table('pagamento')->insert([
            'id'    => '3',
            'nome'  => 'Cartão de Crédito Amex',
        ]);

        DB::table('pagamento')->insert([
            'id'    => '4',
            'nome'  => 'Cartão de Crédito Elo',
        ]);

        DB::table('pagamento')->insert([
            'id'    => '5',
            'nome'  => 'Cartão de Crédito Hipercard',
        ]);

        DB::table('pagamento')->insert([
            'id'    => '6',
            'nome'  => 'Conta Corrente',
        ]);
    }
}
