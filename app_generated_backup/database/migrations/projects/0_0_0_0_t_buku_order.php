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
            $table->string('tipe_order',10);
            $table->string('no_buku_order',20)->nullable();
            $table->integer('m_customer_id')->comment('{"src":"m_customer.id"}');
            $table->string('jenis_barang',250);
            $table->integer('sektor')->comment('{"src":"set.m_general.id"}');
            $table->string('tujuan_asal',100);
            $table->string('moda_transportasi',50);
            $table->string('coo',50)->nullable();
            $table->string('hc',50);

            $table->string('status',10);
            $table->date('tanggal_closing_doc');
            $table->time('jam_closing_doc');
            $table->date('tanggal_closing_cont');
            $table->time('jam_closing_cont');
            $table->string('no_bl',50);
            $table->date('tanggal_bl');
            $table->string('no_invoice',50);
            $table->date('tanggal_invoice');
            $table->date('tanggal_pengkont'); 
            $table->date('tanggal_pemasukan');
            // $table->string('tipe_kontainer',50);
            // $table->string('jenis_kontainer',50);
            $table->integer('jumlah_coo');
            $table->integer('lembar_coo');
            $table->integer('jumlah_coo_ulang');
            $table->integer('lembar_coo_ulang');
            $table->string('kode_pelayaran',20);
            $table->string('nama_pelayaran',250);

            // $table->integer('jumlah_cont_20');
            // $table->integer('jumlah_cont_40');
            // $table->integer('jumlah_cont_45');
            // $table->integer('jumlah_cont_60');

            $table->string('no_boking',20);
            $table->string('voyage',20);
            $table->integer('lokasi_stuffing')->comment('{"src":"m_lokasistuffing.id"}');
            $table->string('gw',20);
            $table->string('nw',20);
            $table->text('catatan')->nullable();

            $table->string('nama_kapal',100);
            $table->string('nama_pelabuhan',100);
            $table->boolean('dispensasi_closing_cont')->default(true);
            $table->boolean('dispensasi_closing_doc')->default(true);
            $table->string('angkutan',100);
            $table->integer('jumlah_kemasan');

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