<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ttagihan extends Migration
{
    protected $tableName = "t_tagihan";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['tarif_ppjk' ]);
            // $table->decimal('grand_total',18,4)->nullable();
            // $table->decimal('total_kontainer',18,4)->nullable();
            // $table->decimal('total_lain',18,4)->nullable();
            // $table->decimal('total_ppn',18,4)->nullable();
            // $table->decimal('total_setelah_ppn',18,4)->nullable();
            // $table->decimal('total_tarif_jasa',18,4)->nullable();
            // $table->string('tipe_tagihan')->nullable()->change();
            // $table->integer('no_faktur_pajak')->comment('{"src":"m_faktur_pajak_d.id"}')->change();
            // $table->decimal('piutang',18,4)->nullable();
        });
    }
}
