<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ttagihandlain extends Migration
{
    protected $tableName = "t_tagihan_d_lain";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->decimal('tarif_realisasi', 18, 2)->nullable();
            // $table->integer('qty')->nullable();
            // $table->boolean('is_ppn')->nullable();

        });
    }
}
