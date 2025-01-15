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
            // $table->dropColumn(['jenis_sj' ]);
            // $table->integer('t_spk_angkutan_id')->comment('{"src":"t_spk_angkutan.id"}')->nullable();
            // $table->string('trip',20)->nullable();
            // $table->string('foto_berkas')->nullable();
            // $table->string('lokasi_stuffing')->nullable();
            // $table->string('depo')->nullable();
            // $table->integer('no_container')->comment('{"src":"t_buku_order.id"}')->nullable();
            // $table->integer('no_container')->comment('{"src":"t_buku_order_d_npwp.id"}')->nullable();
            // $table->renameColumn('no_container_id','t_buku_order_d_npwp_id');
            // $table->integer('ukuran_kontainer')->nullable();
            // $table->string('jenis_kontainer')->nullable();
            // $table->integer('jenis_sj')->comment('{"src":"set.m_general.id"}')->nullable();
            // $table->boolean('is_edit_berkas')->nullable()->default(0);
            // $table->date('tanggal')->nullable()->change();
        });
    }
}
