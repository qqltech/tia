<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class msupplier extends Migration
{
    protected $tableName = "m_supplier";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->integer('top')->comment('{"src": "set.m_general.id"}');
            // $table->integer('bank')->comment('{"src":"set.m_general.id"}')->nullable()->change();
            // $table->string('kode_bank', 10)->nullable()->change();
        });
    }
}
