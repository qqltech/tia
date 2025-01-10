<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpembayaranpiutangd extends Migration
{
    protected $tableName = "t_pembayaran_piutang_d";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['pph_id']);
            // $table->bigInteger('pph_id')->nullable()->comment('{"src":"set.m_general.id"}');
            // $table->text('bukti_potong')->nullable();
            // $table->decimal('total_pph',18,4)->nullable();
        });
    }
}
