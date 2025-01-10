<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tbukuorderdnpwp extends Migration
{
    protected $tableName = "t_buku_order_d_npwp";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['t_buku_order_id_src']);
            // $table->integer('t_buku_order_id_src')->comment('{"src":"t_buku_order.id"}')->nullable();
            $table->integer('depo')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->integer('m_petugas_pengkont_id')->comment('{"src":"set.m_kary.id"}')->nullable();
            $table->integer('m_petugas_pemasukan_id')->comment('{"src":"set.m_kary.id"}')->nullable();
        });
    }
}
