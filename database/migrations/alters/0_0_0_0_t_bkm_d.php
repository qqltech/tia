<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tbkmd extends Migration
{
    protected $tableName = "t_bkm_d";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->integer('t_bkm_id')->comment('{"fk":"t_bkm.id"}')->nullable(true)->change();
            //$table->dropColumn([ ]);
            $table->text('keterangan')->nullable(true)->change();
        });
    }
}
