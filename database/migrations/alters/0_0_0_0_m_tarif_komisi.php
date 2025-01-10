<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mtarifkomisi extends Migration
{
    protected $tableName = "m_tarif_komisi";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->string('tipe_komisi')->nullable();
            // $table->bigInteger('m_customer_id')->comment('{"src":"m_customer.id"}')->nullable();
            // $table->bigInteger('tipe_order')->comment('{"src":"set.m_general.id"}')->nullable();
        });
    }
}
