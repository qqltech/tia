<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tnotarampungd extends Migration
{
    protected $tableName = "t_nota_rampung_d";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['ukuran_kontainer_id']);
            // $table->string('no_kontainer',30)->nullable()->change();
            // $table->integer('ukuran_kontainer_id');
            // $table->integer('tipe_kontainer_id');
            // $table->integer('jenis_kontainer_id');
            // $table->integer('sektor_id');
            // $table->integer('t_nota_rampung_id')->comment('{"fk":"t_nota_rampung.id"}')->nullable()->change();
            // $table->integer('ukuran_kontainer');
        });
    }
}
