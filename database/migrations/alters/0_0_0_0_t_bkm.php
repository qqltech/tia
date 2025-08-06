<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tbkm extends Migration
{
    protected $tableName = "t_bkm";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
             $table->string('no_reference',50)->nullable();
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['t_buku_order_id','m_coa_id']);
            // $table->text('keterangan')->nullable(true)->change();
            // $table->integer('m_business_unit_id')->comment('{"src":"set.m_business_unit.id"}')->nullable()->change();
            // $table->string('nama_penyetor', 100)->nullable();
            // $table->renameColumn('nama_penyetor','nama_penerima');
        });
    }
}
