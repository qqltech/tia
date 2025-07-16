<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpembayaranpiutang extends Migration
{
    protected $tableName = "t_pembayaran_piutang";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
                    //    $table->integer('customer')->unique(false)->comment('{"src":"m_customer.id"}')->change();

            //$table->dropColumn([ ]);
            // $table->integer('m_akun_bank_id')->comment('{"src":"m_coa.id"}')->nullable();
            $table->integer('tipe_piutang')->comment('{"src":"set.m_general.id"}')->nullable();
        });
    }
}
