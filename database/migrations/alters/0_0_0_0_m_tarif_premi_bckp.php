<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mtarifpremibckp extends Migration
{
    protected $tableName = "m_tarif_premi_bckp";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn("tipe_kontainer");
            // $table->integer('grup_head_id')->comment('{"src":"m_tarif_premi_bckp.id"}');
            // $table->decimal('tambahan_tagihan',18,4)->nullable();
            // $table->decimal('tambahan_premi',18,4)->nullable();
            // $table->decimal('ganti_solar',18,4)->nullable();
            // $table->decimal('ganti_solar_premi',18,4)->nullable();
            // $table->decimal('tagihan_lain_lain',18,4)->nullable();
            // $table->decimal('lain_lain',18,4)->nullable();						
            // $table->decimal('premig',18,4)->nullable();
            // $table->decimal('gansoltag',18,4)->nullable();
            // $table->decimal('gansolgab',18,4)->nullable();
            // $table->decimal('gansol1',18,4)->nullable();
            // $table->decimal('gansol2',18,4)->nullable();
            // $table->decimal('gansol3',18,4)->nullable();
            // $table->decimal('gansol4',18,4)->nullable();
            // $table->decimal('gansol5',18,4)->nullable();
            // sektor_id, ukuran_container, tagihan, premi
            // $table->integer('sektor_id')->comment('{"src":"set.m_general.id"}')->nullable()->change();
            // $table->integer('ukuran_container')->comment('{"src":"set.m_general.id"}')->nullable()->change();
            // $table->decimal('tagihan',18,4)->nullable()->change();
            // $table->decimal('premi',18,4)->nullable()->change();
            // $table->integer('grup_head_id')->comment('{"src":"m_grup_head.id"}')->nullable()->change();
            // $table->integer('trip')->comment('{"src":"set.m_general.id"}')->nullable()->change();
            // $table->decimal('sangu',18,4)->nullable()->change();
        });
    }
}
