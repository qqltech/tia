<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tangkutand extends Migration
{
    protected $tableName = "t_angkutan_d";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->integer('t_angkutan_id')->comment('{"fk":"t_angkutan.id"}')->nullable();
            $table->integer('t_spk_id')->comment('{"src":"t_spk_angkutan.id"}')->nullable();
            $table->integer('depo')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->integer('ukuran')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->integer('sektor')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->integer('head')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->integer('trip')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->date('tanggal_out')->nullable();
            $table->time('jam_out')->nullable();
            $table->bigInteger('waktu_out')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->date('tanggal_in')->nullable();
            $table->time('jam_in')->nullable();
            $table->bigInteger('waktu_in')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->integer('free')->default(0)->nullable();
            $table->decimal('tarif_los_cargo',18,2)->default(0)->nullable();
            $table->bigInteger('pelabuhan')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->integer('angkutan_pelabuhan')->comment('{"src":"m_supplier.id"}')->nullable();
            $table->integer('nama_angkutan_id')->comment('{"src":"m_supplier.id"}')->nullable();
            $table->string('staple')->nullable();
            $table->string('no_container')->nullable();
            $table->decimal('biaya_lain_lain',18,4)->nullable();
            $table->date('tgl_stuffing')->nullable();
            $table->text('catatan')->nullable();

            //penting
            $table->integer("creator_id")->nullable();
            $table->integer("last_editor_id")->nullable();
            $table->datetime("edited_at")->nullable();
            $table->integer("deletor_id")->nullable();
            $table->datetime("deleted_at")->nullable();
            $table->timestamps();
        });

        table_config($this->tableName, [
            "guarded" => ["id"],
            "required" => [],
            "!createable" => ["id", "created_at", "updated_at"],
            "!updateable" => ["id", "created_at", "updated_at"],
            "searchable" => "all",
            "deleteable" => "true",
            "deleteOnUse" => "false",
            "extendable" => "false",
            "casts" => [
                "created_at" => "datetime:d/m/Y H:i",
                "updated_at" => "datetime:d/m/Y H:i",
            ],
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
