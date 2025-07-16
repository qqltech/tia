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
            //$table->dropColumn([ ]);
            // $table->boolean('kena_pajak')->default(0)->nullable();
        });
    }
}
