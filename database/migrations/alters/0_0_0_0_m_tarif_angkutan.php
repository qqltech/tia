<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mtarifangkutan extends Migration
{
    protected $tableName = "m_tarif_angkutan";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            // $table->integer('m_supplier_id')->comment('{"src":"m_supplier.id"}')->nullable(0)->change();
            //$table->string('_columnName_');
            // $table->dropColumn([ 'kena_pajak']);
            // $table->boolean('kena_pajak')->default(0)->nullable(0)->change();
            // $table->string('kode',20)->nullable(0)->change();
            // $table->boolean('kena_pajak')->default(0)->nullable();
            // $table->integer('jenis')->comment('{"src":"set.m_general.id"}')->nullable()->change();
            // $table->decimal('ganti_solar_muter',18,4)->nullable();
            // $table->decimal('ganti_solar_lain',18,4)->nullable();
            // $table->decimal('atur_stapel_1',18,4)->nullable();
            // $table->decimal('atur_stapel_2',18,4)->nullable();
            // $table->decimal('tambahan_lain_1',18,4)->nullable();
            // $table->decimal('tambahan_lain_2',18,4)->nullable();
            // $table->integer('ppn_id')->comment('{"src":"set.m_general.id"}')->nullable();
        });
    }
}
