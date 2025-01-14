<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tbukuorder extends Migration
{
    protected $tableName = "t_buku_order";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            // $table->renameColumn('no_etd_eta','tgl_etd_eta');
            //$table->string('_columnName_');

            // $table->dropColumn(['berkas_coo']);
            // $table->string('tipe_order',10)->nullable()->change();
            // $table->string('no_buku_order',20)->nullable();
            // $table->integer('m_customer_id')->comment('{"src":"m_customer.id"}')->nullable()->change();
            // $table->string('jenis_barang',250)->nullable()->change();
            // $table->integer('sektor')->comment('{"src":"set.m_general.id"}')->nullable()->change();
            // $table->string('tujuan_asal',100)->nullable()->change();
            // $table->string('moda_transportasi',50)->nullable()->change();
            // // $table->string('coo',50)->nullable();
            // $table->string('hc',50)->nullable()->change();

            // $table->string('status',10)->nullable()->change();
            // $table->date('tanggal_closing_doc')->nullable()->change();
            // $table->time('jam_closing_doc')->nullable()->change();
            // $table->date('tanggal_closing_cont')->nullable()->change();
            // $table->time('jam_closing_cont')->nullable()->change();
            // $table->string('no_bl',50)->nullable()->default('-')->change();
            // $table->date('tanggal_bl')->nullable()->change();
            // $table->string('no_invoice',50)->nullable()->default('-')->change();
            // $table->date('tanggal_invoice')->nullable()->change();
            // $table->date('tanggal_pengkont')->nullable()->change(); 
            // $table->date('tanggal_pemasukan')->nullable()->change();

            // $table->integer('jumlah_coo')->nullable()->default(0)->change();
            // $table->integer('lembar_coo')->nullable()->default(0)->change();
            // $table->integer('jumlah_coo_ulang')->nullable()->default(0)->change();
            // $table->integer('lembar_coo_ulang')->nullable()->default(0)->change();
            // $table->integer('kode_pelayaran_id')->comment('{"src":"set.m_general.id"}')->nullable()->change();
            // $table->string('nama_pelayaran',250)->nullable()->default('-')->change();

            // $table->string('no_boking',20)->nullable()->default('-')->change();
            // $table->string('voyage',20)->nullable()->change();
            // $table->integer('lokasi_stuffing')->comment('{"src":"m_customer_d_address.id"}')->nullable()->change();
            // $table->string('gw',20)->nullable()->change();
            // $table->string('nw',20)->nullable()->change();
            // // $table->text('catatan')->nullable();

            // $table->string('nama_kapal',100)->nullable()->change();
            // $table->integer('pelabuhan_id')->comment('{"src":"set.m_general.id"}')->nullable();
            // $table->boolean('dispensasi_closing_cont')->default(true)->nullable()->change();
            // $table->boolean('dispensasi_closing_doc')->default(true)->nullable()->change();
            // $table->string('angkutan',100)->nullable()->default('-')->change();
            // $table->integer('jumlah_kemasan')->nullable()->default(0)->change();
            // $table->string('lokasi_stuffing')->nullable();
            // $table->string('berkas_coo')->nullable();
            // $table->integer('tipe')->comment('{"src":"set.m_general.id"}')->nullable();
            // $table->date('tgl_etd_eta')->nullable();
            $table->string('genzet',100)->nullable();
        });
    }
}
