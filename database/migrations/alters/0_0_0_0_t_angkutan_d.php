<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tangkutand extends Migration
{
    protected $tableName = "t_angkutan_d";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['angkutan_pelabuhan' ]);
            //  $table->decimal('tarif_los_cargo',18,2)->change();
            // $table->integer('t_spk_id')->comment('{"src":"t_spk_angkutan.id"}')->nullable()->change();
            // $table->string('waktu_out',20)->nullable();
            // $table->string("angkutan_pelabuhan",40)->nullable();
            // $table->integer('nama_angkutan_id')->comment('{"src":"m_supplier.id"}')->nullable();
            // $table->date('tanggal_in')->nullable();
            // $table->time('jam_in')->nullable();
            // $table->string('waktu_in',20)->nullable();
            // $table->string('staple')->nullable();
            // $table->string('pelabuhan')->nullable();
            //             $table->integer('depo')->comment('{"src":"set.m_general.id"}')->nullable()->change();
            // $table->integer('sektor')->comment('{"src":"set.m_general.id"}')->nullable()->change();
            // $table->date('tanggal_out')->nullable()->change();
            // $table->time('jam_out')->nullable()->change();
            // $table->string('no_container')->nullable();
            // $table->decimal('biaya_lain_lain',18,4)->nullable();
            // $table->date('tgl_stuffing')->nullable();
            // $table->string("no_angkutan")->nullable();
            // $table->integer('head')->comment('{"src":"set.m_general.id"}')->nullable();
            // $table->text('catatan')->nullable();
            // $table->integer('trip')->comment('{"src":"set.m_general.id"}')->nullable();
            // $table->integer('free')->default(0)->nullable()->change();
            // $table->decimal('tarif_los_cargo',18,2)->default(0)->nullable()->change();
            // $table->bigInteger('pelabuhan')->comment('{"src":"set.m_general.id"}')->nullable();
            // $table->bigInteger('waktu_out')->comment('{"src":"set.m_general.id"}')->nullable();
            // $table->bigInteger('waktu_in')->comment('{"src":"set.m_general.id"}')->nullable();
            // $table->integer('angkutan_pelabuhan')->comment('{"src":"m_supplier.id"}')->nullable();
        });
    }
}
