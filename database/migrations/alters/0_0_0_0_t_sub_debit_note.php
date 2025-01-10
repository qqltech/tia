<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tsubdebitnote extends Migration
{
    protected $tableName = "t_sub_debit_note";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->integer('tipe_perkiraan')->comment('{"src":"set.m_general.id"}')->nullable();
        });
    }
}
