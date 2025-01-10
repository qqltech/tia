<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mbusinessunit extends Migration
{
    protected $tableName = "set.m_business_unit";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            $table->integer('m_business_unit_id')->comment('{"src":"m_business_unit.id"}')->nullable();
        });
    }
}
