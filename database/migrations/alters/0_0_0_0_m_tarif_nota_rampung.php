<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mtarifnotarampung extends Migration
{
    protected $tableName = "m_tarif_nota_rampung";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['tarif_cancel_dok' ]);
            // $table->decimal('tarif_mob',18,4)->nullable()->default(0);
            // $table->decimal('tarif_vgm',18,4)->nullable()->default(0);
            // $table->decimal('tarif_by_adm_nr',18,4)->nullable()->default(0);
            // $table->decimal('tarif_materai',18,4)->nullable()->default(0);
            // $table->decimal('tarif_denda_koreksi',18,4)->nullable()->default(0);
            // $table->decimal('tarif_cancel_dok',18,4)->nullable()->default(0);
            // $table->decimal('tarif_denda_sp',18,4)->nullable()->default(0);
            // $table->decimal('tarif_behandle',18,4)->nullable()->default(0);
            // $table->integer('tipe_tarif')->comment('{"src":"set.m_general.id"}')->nullable();
        });
    }
}
