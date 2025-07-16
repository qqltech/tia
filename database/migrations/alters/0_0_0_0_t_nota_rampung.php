<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tnotarampung extends Migration
{
    protected $tableName = "t_nota_rampung";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['tgl_nr']);
            // $table->float('kurs')->nullable(0)->default(1)->change();
            // $table->string("foto_scn")->nullable();
            // $table->decimal('lolo',18,4)->nullable()->change();
            // $table->decimal('m2',18,4)->nullable()->change();
            // $table->decimal('ow',18,4)->nullable()->change();
            // $table->decimal('m3',18,4)->nullable()->change();
            // $table->decimal('m4',18,4)->nullable()->change();
            // $table->decimal('m5',18,4)->nullable()->change();
            // $table->decimal('plg_mon',18,4)->nullable()->change();
            // $table->decimal('ge',18,4)->nullable()->change();
            // $table->decimal('strp_stuf',18,4)->nullable()->change();
            // $table->decimal('canc_doc',18,4)->nullable()->change();
            // $table->decimal('closing_container',18,4)->nullable()->change();
            // $table->decimal('batal_muat',18,4)->nullable()->change();
            // $table->decimal('vgm',18,4)->nullable()->change();
            // $table->decimal('lolo_non_sp',18,4)->nullable()->change();
            // $table->decimal('m1',18,4)->nullable();
            // $table->string('no_stack',50)->nullable();
            // $table->date('tgl_stack')->nullable();
            // $table->string('no_eir',50)->nullable();
            // $table->date('tgl_eir')->nullable();
            // $table->text('foto_scn')->nullable()->change();
            // $table->integer('tipe_tarif')->comment('{"src":"set.m_general.id"}')->nullable();
            // $table->renameColumn('tipe_tarif','tipe_nota_rampung');
        });
    }
}
