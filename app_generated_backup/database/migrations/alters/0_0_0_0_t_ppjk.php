<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tppjk extends Migration
{
    protected $tableName = "t_ppjk";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->decimal('nilai_kurs',18,2)->nullable()->change();
            // $table->decimal('nilai_kurs',18,4)->nullable()->change();
            
            // $table->decimal('invoice',18,4)->nullable()->change();
            // $table->decimal('ppn_pib',18,4)->nullable()->change();
        });
    }
}
