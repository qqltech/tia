<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tbkknonorder extends Migration
{
    protected $tableName = "t_bkk_non_order";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->integer('m_perkiraan_id')->comment('{"src":"m_coa.id"}')->nullable();
            // $table->integer('tipe_bkk')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->integer('m_business_unit_id')->comment('{"src":"set.m_business_unit.id"}')->nullable()->change();
        });
    }
}
