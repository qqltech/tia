<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ttagihan extends Migration
{
    protected $tableName = "t_tagihan";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->decimal('tarif_coo',18,4)->nullable();
            // $table->decimal('tarif_ppjk',18,4)->nullable();
        });
    }
}
