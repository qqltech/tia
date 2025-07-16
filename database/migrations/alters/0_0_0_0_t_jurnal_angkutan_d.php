<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tjurnalangkutand extends Migration
{
    protected $tableName = "t_jurnal_angkutan_d";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            $table->renameColumn('m_jurnal_angkutan_id','t_jurnal_angkutan_id');
        });
    }
}
