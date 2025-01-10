<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tbukupenyesuaian extends Migration
{
    protected $tableName = "t_buku_penyesuaian";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->renameColumn('no_buku_penyesuain','no_buku_penyesuaian');
        });
    }
}
