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
            // $table->renameColumn('sektor','sektor1');
            // $table->dropColumn(['waktu_out','waktu_in' ]);
            // $table->string('no_spk', 50)->nullable();
            // $table->integer('tipe_spk')->comment('{"src":"set.m_general.id"}')->nullable()->change();
            // $table->integer('depo')->comment('{"src":"set.m_general.id"}')->nullable()->change();
            // $table->string('status')->nullable()->change();
            // $table->integer('t_buku_order_1_id')->comment('{"src":"t_buku_order.id"}')->nullable()->change();
            // $table->integer('t_detail_npwp_container_1_id')->comment('{"src":"t_buku_order_d_npwp.id"}')->nullable()->change();
            // $table->integer('isi_container_1')->comment('{"src":"set.m_general.id"}')->nullable()->change();
            // $table->string('no_container_1')->nullable()->change();

            // $table->boolean('is_con_edit')->nullable()->default(0)->change();

            // // $table->integer('t_buku_order_2_id')->comment('{"src":"t_buku_order.id"}')->nullable();
            // // $table->integer('t_detail_npwp_container_2_id')->comment('{"src":"t_buku_order_d_npwp.id"}')->nullable();
            // // $table->integer('isi_container_2')->comment('{"src":"set.m_general.id"}')->nullable();
            // // $table->string('no_container_2')->nullable();

            // $table->date('tanggal_spk')->nullable()->change();
            // $table->integer('supir')->comment('{"src":"set.m_kary.id"}')->nullable()->change();
            // $table->integer('sektor2')->comment('{"src":"set.m_general.id"}')->nullable();
            // $table->integer('head')->comment('{"src":"set.m_general.id"}')->nullable()->change();
            // $table->integer('chasis')->comment('{"src":"set.m_general.id"}')->nullable()->change();
            // $table->string('dari', 50)->nullable()->change();
            // $table->string('ke', 10)->nullable()->change();
            // // $table->decimal('sangu',18,2)->nullable();
            // // $table->decimal('total_sangu',18,2)->nullable();
            // $table->date('tanggal_out')->nullable()->change();
            // $table->string('waktu_out',10)->nullable()->change();
            // $table->date('tanggal_in')->nullable()->change();
            // $table->string('waktu_in',10)->nullable()->change();
            // // $table->text('catatan')->nullable();
            // // $table->integer('m_supplier_id')->comment('{"src":"m_supplier.id"}')->nullable();
            // $table->integer('trip_id')->comment('{"src":"set.m_general.id"}')->nullable();
            // $table->integer('jenis_container_1')->comment('{"src":"set.m_general.id"}')->nullable();
            // $table->integer('jenis_container_2')->comment('{"src":"set.m_general.id"}')->nullable();
            // $table->integer('chasis2')->comment('{"src":"set.m_general.id"}')->nullable();
            // $table->string('dari', 100)->nullable();
            // $table->string('ke', 100)->nullable();
            // $table->bigInteger('nama_customer')->nullable()->comment('{"src":"m_customer.id"}');
            // $table->bigInteger('nama_customer_2')->nullable()->comment('{"src":"m_customer.id"}');
            $table->bigInteger('waktu_out')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->bigInteger('waktu_in')->comment('{"src":"set.m_general.id"}')->nullable();
            
        });

    }
}
