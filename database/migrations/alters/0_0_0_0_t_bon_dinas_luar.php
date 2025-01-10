<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tbondinasluar extends Migration
{
    protected $tableName = "t_bon_dinas_luar";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->bigInteger('t_bkk_id')->comment('{"src":"t_bkk.id"}')->nullable()->change();
            $table->bigInteger('tipe_kategori_id')->comment('{"src":"m_coa.id"}')->change();
        });
    }
}
