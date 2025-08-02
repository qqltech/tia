<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tbkmnonorder extends Migration
{
    protected $tableName = "t_bkm_non_order";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
           $table->string('nama_penerima', 100)->nullable();
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->integer('m_business_unit_id')->comment('{"src":"set.m_business_unit.id"}')->nullable()->change();
            // $table->string('nama_penyetor', 100)->nullable();
            // $table->renameColumn('nama_penyetor','nama_penerima');
        });
    }
}
