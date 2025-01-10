<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tbukuorder extends Migration
{
    protected $tableName = "t_buku_order";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['m_petugas_pengkont_id','m_petugas_pemasukan_id' ]);
            // $table->integer('sektor')->change();
            // $table->dropColumn(string('tipe_kontainer',50));
            // $table->dropColumn(string('jenis_kontainer',50));
            // $table->string('coo',50)->nullable()->change();
        });
    }
}
