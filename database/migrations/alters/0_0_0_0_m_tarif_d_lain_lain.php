<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mtarifdlainlain extends Migration
{
    protected $tableName = "m_tarif_d_lain_lain";

    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->renameColumn('keterangan','deskripsi');
            // $table->string("deskripsi")->nullable(0)->change();
            // $table->decimal("nominal", 18, 4)->nullable(0)->change();
            $table->bigInteger('satuan_id')->comment('{"src":"set.m_general.id"}')->nullable();
        });
    }
}
