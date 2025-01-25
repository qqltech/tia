<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tbondinasluard extends Migration
{
    protected $tableName = "t_bon_dinas_luar_d";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            $table->dropColumn(['ukuran_container']);
            // $table->integer('ukuran_container');
        });
    }
}
