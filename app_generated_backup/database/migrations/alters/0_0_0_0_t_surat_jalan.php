<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tsuratjalan extends Migration
{
    protected $tableName = "t_surat_jalan";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['trip' ]);
            // $table->integer('t_spk_angkutan_id')->comment('{"src":"t_spk_angkutan.id"}')->nullable();
            // $table->string('trip',20)->nullable();
            // $table->string('foto_berkas')->nullable();
        });
    }
}
