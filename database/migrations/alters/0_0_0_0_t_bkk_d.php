<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tbkkd extends Migration
{
    protected $tableName = "t_bkk_d";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            $table->integer('t_bkk_id')->comment('{"fk":"t_bkk.id"}')->nullable()->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
        });
    }
}
