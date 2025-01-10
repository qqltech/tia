<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tbkk extends Migration
{
    protected $tableName = "t_bkk";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['no_reference']);
            // $table->integer('m_business_unit_id')->comment('{"src":"set.m_business_unit.id"}')->nullable()->change();
        });
    }
}
