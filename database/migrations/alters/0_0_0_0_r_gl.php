<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class rgl extends Migration
{
    protected $tableName = "r_gl";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {

            // $table->integer('m_business_unit_id')->comment('{"src":"set.m_business_unit.id"}')->nullable();
            $table->string('no_reference', 100)->nullable();
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
        });
    }
}
