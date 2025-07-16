<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tspklain extends Migration
{
    protected $tableName = "t_spk_lain";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('no_draft')->nullable();
            $table->string('status')->nullable();
            $table->string('no_spk')->nullable();
            $table->date('tanggal')->nullable();
            $table->integer('genzet')->comment('{"src":"m_supplier.id"}');
            $table->integer('no_container')->comment('{"src":"t_buku_order_d_npwp.id"}');
            $table->integer('t_buku_order_id')->comment('{"src":"t_buku_order.id"}')->nullable();
            $table->integer('m_customer_id')->comment('{"src":"m_customer.id"}')->nullable();
            $table->integer('ukuran')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->string('setting_temperatur',100);
            $table->date('keluar_lokasi_tanggal')->nullable();
            $table->time('keluar_lokasi_jam')->nullable();
            $table->string('keluar_lokasi_temperatur',100)->nullable();
            $table->date('tiba_lokasi_tanggal')->nullable();
            $table->time('tiba_lokasi_jam')->nullable();
            $table->string('tiba_lokasi_temperatur',100)->nullable();
            $table->text('lokasi_stuffing')->nullable();
            $table->text('catatan')->nullable();
            //penting
            $table->integer('creator_id')->nullable();
            $table->integer('last_editor_id')->nullable();
            $table->datetime("edited_at")->nullable();
            $table->integer("deletor_id")->nullable();
            $table->datetime("deleted_at")->nullable();
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