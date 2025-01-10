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
            $table->dropColumn(['tipe']);
            // $table->string('no_kontainer',30)->nullable()->change();
            // $table->integer('ukuran_kontainer_id');
            // $table->integer('tipe_kontainer_id');
            // $table->integer('jenis_kontainer_id');
            // $table->integer('sektor_id');
            // $table->integer('t_nota_rampung_id')->comment('{"fk":"t_nota_rampung.id"}')->nullable()->change();
            // $table->integer('ukuran_kontainer');
            // $table->integer('t_buku_order_d_npwp_id')->comment('{"src":"t_buku_order_d_npwp.id"}')->nullable();
            // $table->decimal('lolo',18,4)->nullable();
            // $table->decimal('m2',18,4)->nullable();
            // $table->decimal('ow',18,4)->nullable();
            // $table->decimal('m3',18,4)->nullable();
            // $table->decimal('m4',18,4)->nullable();
            // $table->decimal('m5',18,4)->nullable();
            // $table->decimal('plg_mon',18,4)->nullable();
            // $table->decimal('ge',18,4)->nullable();
            // $table->decimal('strp_stuf',18,4)->nullable();
            // $table->decimal('canc_doc',18,4)->nullable();
            // $table->decimal('closing_container',18,4)->nullable();
            // $table->decimal('batal_muat',18,4)->nullable();
            // $table->string('spek_kont',100)->nullable();
        });
    }
}
