<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpembayaranhutangd extends Migration
{
    protected $tableName = "t_pembayaran_hutang_d";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn([ 'pph_id']);
            // $table->string('no_pi')->nullable();
            // $table->date('tgl_pi')->nullable();
            // $table->date('tgl_jt')->nullable();
            // $table->decimal('nilai_hutang',18,4)->nullable();
            // $table->decimal('sisa_hutang',18,4)->nullable();
            // $table->decimal('bayar',18,4)->nullable();
            // $table->integer('t_jurnal_angkutan_id')->comment('{"src":"t_jurnal_angkutan.id"}')->nullable();    
            
        });
    }
}
