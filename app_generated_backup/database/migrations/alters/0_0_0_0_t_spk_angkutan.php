<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tspkangkutan extends Migration
{
    protected $tableName = "t_spk_angkutan";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['sangu' ]);
            // $table->integer('sektor')->comment('{"src":"set.m_general.id"}')->nullable();
            // $table->decimal('sangu',18,2)->nullable()->change();
            // $table->string('no_spk', 50)->nullable()->change();
            // $table->integer('t_buku_order_2_id')->comment('{"src":"t_buku_order.id"}')->nullable()->change();
            // $table->integer('t_detail_npwp_container_2_id')->comment('{"src":"t_buku_order_d_npwp.id"}')->nullable()->change();
            // $table->integer('isi_container_2')->comment('{"src":"set.m_general.id"}')->nullable()->change();
            // $table->string('no_container_2')->nullable()->change();
            // $table->integer('t_tipe_kontainer_1_id')->comment('{"src":"t_buku_order_d_npwp.id"}')->nullable();
            // $table->integer('t_tipe_kontainer_2_id')->comment('{"src":"t_buku_order_d_npwp.id"}')->nullable();
            //  $table->decimal('total_sangu',18,2)->nullable();
            // $table->integer('depo')->comment('{"src":"set.m_general.id"}')->nullable(false)->change();
            // $table->integer('m_supplier_id')->comment('{"src":"m_supplier.id"}')->nullable()->change();
        });

    }
}
