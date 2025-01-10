<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpurchaseinvoice extends Migration
{
    protected $tableName = "t_purchase_invoice";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            // $table->integer('tipe_pembayaran_id')->comment('{"src":"set.general.id"}')->change();
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            $table->dropColumn(['tipe_pembayaran_id' ]);
            // $table->date('tgl_jatuh_tempo')->nullable();
            // $table->string('termin')->nullable();
            // $table->decimal('utang',18,4)->nullable();
            // $table->renameColumn('m_faktur_pajak_d_id','no_faktur_pajak');
            // $table->integer('m_faktur_pajak_d_id')->comment('{"src":"m_faktur_pajak_d.id"}')->change();
            // $table->integer('tipe_pembayaran_id')->comment('{"src":"set.m_general.id"}')->change();
        });
    }
}
