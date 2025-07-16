<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class cobamasterbarang extends Migration
{
    protected $tableName = "coba_master_barang";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            $table->string('supplier')->nullable();
            //$table->dropColumn([ ]);
        });
    }
}
