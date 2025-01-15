<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tsuratjalan extends Migration
{
    protected $tableName = "t_surat_jalan";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);

            $table->string('no_draft',40)->nullable();
            $table->string('no_surat_jalan',40)->nullable();
            
            
            $table->integer('t_buku_order_id')->comment('{"src":"t_buku_order.id"}');
            $table->date('tanggal')->nullable();
            $table->date('tanggal_berangkat');
            $table->string('status')->default("DRAFT")->nullable();
            $table->string('tipe_surat_jalan')->nullable();
            $table->string('lokasi_stuffing')->nullable();
            $table->string('depo')->nullable();
            $table->integer('t_buku_order_d_npwp_id')->comment('{"src":"t_buku_order_d_npwp.id"}')->nullable();
            $table->string('pelabuhan')->nullable();
            $table->string('kapal')->nullable();
            $table->text('catatan')->nullable();
            $table->string('foto_berkas')->nullable();
            $table->integer('ukuran_kontainer')->nullable();
            $table->string('jenis_kontainer')->nullable();
            $table->integer('jenis_sj')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->boolean('is_edit_berkas')->nullable()->default(0);
            //penting
            $table->integer("creator_id")->nullable();
            $table->integer("last_editor_id")->nullable();
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