<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tdinasluard extends Migration
{
    protected $tableName = "t_dinas_luar_d";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            $table->decimal('nominal', 18, 4)->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
        });
    }
}
