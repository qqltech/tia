<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mtarifpremi extends Migration
{
    protected $tableName = "m_tarif_premi";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('no_tarif_premi',50)->nullable();
            $table->integer('m_spk_angkutan_id')->comment('{"src":"t_spk_angkutan.id"}')->nullable();
            $table->string('kode_jalan',20)->nullable();
            $table->integer('tipe_kontainer')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->string('premi',200)->nullable();
            $table->decimal('ganti_solar_premi',18,4)->nullable();
            $table->boolean('is_active')->default(1)->nullable();
            $table->integer('m_sektor_id')->comment('{"src":"set.m_general.id"}');
            $table->decimal('sangu',18,4)->nullable();
            $table->integer('tagihan')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->decimal('ganti_solar_tagihan',18,4)->nullable();
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