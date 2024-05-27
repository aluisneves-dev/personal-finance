<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLancamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lancamento', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('data');
            $table->string('tipo',100);
            $table->foreignId('categoria_id')->references('id')->on('categoria')->onDelete('restrict');
            $table->string('descricao',100);
            $table->decimal('valor',10,2)->nullable()->default(null)->signed();
            $table->date('vencimento');
            $table->char('parcela');
            $table->char('parcelaTotal');
            $table->foreignId('pagamento_id')->references('id')->on('pagamento')->onDelete('restrict');
            $table->foreignId('credor_id')->references('id')->on('credor')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lancamento');
    }
}
