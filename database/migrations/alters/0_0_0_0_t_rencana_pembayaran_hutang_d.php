<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class trencanapembayaranhutangd extends Migration
{
    protected $tableName = "t_rencana_pembayaran_hutang_d";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['t_tagihan_id' ]);
            // $table->integer('t_jurnal_angkutan_id')->comment('{"src":"t_jurnal_angkutan.id"}')->nullable();
            // $table->integer('t_purchase_invoice_id')->comment('{"src":"t_purchase_invoice.id"}')->nullable();
            // $table->integer('m_supplier_id')->comment('{"src":"m_supplier.id"}')->nullable();
            $table->date('tanggal_realisasi')->nullable();
            $table->decimal('bayar',18,4)->nullable();
            $table->integer('tipe_pembayaran_id')->comment('{"src":"set.m_general.id"}')->nullable();
        });
    }
}
