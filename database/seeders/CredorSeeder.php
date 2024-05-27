<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CredorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('credor')->insert([
            'id'    => '1',
            'nome'  => 'Caixa Econômica Federal',
        ]);
    }
}
