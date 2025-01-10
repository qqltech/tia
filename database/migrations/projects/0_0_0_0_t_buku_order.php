<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tbukuorder extends Migration
{
    protected $tableName = "t_buku_order";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->date('tgl');
            $table->string('tipe_order',10)->nullable();
            $table->string('no_buku_order',20)->nullable();
            $table->integer('m_customer_id')->comment('{"src":"m_customer.id"}')->nullable();
            $table->string('jenis_barang',250)->nullable();
            $table->integer('sektor')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->string('tujuan_asal',100)->nullable();
            $table->string('moda_transportasi',50)->nullable();
            $table->string('coo',50)->nullable();
            $table->string('hc',50)->nullable();

            $table->string('status',10)->nullable();
            $table->date('tanggal_closing_doc')->nullable();
            $table->time('jam_closing_doc')->nullable();
            $table->date('tanggal_closing_cont')->nullable();
            $table->time('jam_closing_cont')->nullable();
            $table->string('no_bl',50)->nullable()->default('-');
            $table->date('tanggal_bl')->nullable();
            $table->string('no_invoice',50)->nullable()->default('-');
            $table->date('tanggal_invoice')->nullable();
            $table->date('tanggal_pengkont')->nullable(); 
            $table->date('tanggal_pemasukan')->nullable();

            $table->integer('jumlah_coo')->nullable()->default(0);
            $table->integer('lembar_coo')->nullable()->default(0);
            $table->integer('jumlah_coo_ulang')->nullable()->default(0);
            $table->integer('lembar_coo_ulang')->nullable()->default(0);
            $table->integer('kode_pelayaran_id')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->string('nama_pelayaran',250)->nullable()->default('-');

            $table->string('no_boking',20)->nullable()->default('-');
            $table->string('voyage',20)->nullable();
            $table->integer('lokasi_stuffing')->comment('{"src":"m_customer_d_address.id"}')->nullable();
            $table->string('gw',20)->nullable();
            $table->string('nw',20)->nullable();
            $table->text('catatan')->nullable();

            $table->string('nama_kapal',100)->nullable();
            $table->integer('pelabuhan_id')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->boolean('dispensasi_closing_cont')->default(true)->nullable();
            $table->boolean('dispensasi_closing_doc')->default(true)->nullable();
            $table->string('angkutan',100)->nullable()->default('-');
            $table->integer('jumlah_kemasan')->nullable()->default(0);
            $table->string('lokasi_stuffing')->nullable();
            // $table->string('berkas_coo')->nullable();
            $table->integer('tipe')->comment('{"src":"set.m_general.id"}')->nullable();

            $table->integer('creator_id')->nullable();
            $table->integer('last_editor_id')->nullable();
            $table->integer('delete_id')->nullable();
            $table->timestamp('delete_at')->nullable();
            $table->timestamps();
        });

        table_config($this->tableName, [
            "guarded"       => ["id"],
            "required"      => [],
            "!createable"   => ["id","created_at","updated_at"],
            "!updateable"   => ["id","created_at","updated_at"],
            "searchable"    => "all",
            "deleteable"    => "true",
            "deleteOnUse"   => "false",
            "extendable"    => "false",
            "casts"     => [
                'created_at' => 'datetime:d/m/Y H:i',
                'updated_at' => 'datetime:d/m/Y H:i'
            ]
        ]);

        // if( $data = \Cache::pull($this->tableName) ){
        //     $fixedData = json_decode( json_encode( $data ), true );
        //     \DB::table($this->tableName)->insert( $fixedData );
        // }
    }
    public function down()
    {
        // if( Schema::hasTable($this->tableName) ){
        //     \Cache::put($this->tableName, \DB::table($this->tableName)->get(), 60*30 );
        // }
        Schema::dropIfExists($this->tableName);
    }
}