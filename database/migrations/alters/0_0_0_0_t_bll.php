<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tbll extends Migration
{
    protected $tableName = "t_bll";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['m_akun_pembayaran_id']);
            // $table->integer('m_coa_id')->comment('{"src":"m_coa.id"}')->change();
        });
    }
}
