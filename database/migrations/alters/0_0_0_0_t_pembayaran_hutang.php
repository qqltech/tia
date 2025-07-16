<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpembayaranhutang extends Migration
{
    protected $tableName = "t_pembayaran_hutang";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->integer('t_rencana_pembayaran_hutang_id')->comment('{"src":"t_rencana_pembayaran_hutang.id"}')->nullable();
            $table->integer('m_akun_bank_id')->comment('{"src":"m_coa.id"}')->nullable();
        });
    }
}
