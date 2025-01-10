<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mtarif extends Migration
{
    protected $tableName = "m_tarif";

    public function up()
    {   
        //relasi sama lokasi stuffing
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('no_tarif',20)->nullable();
            $table->string('tipe_tarif',20)->nullable();
            $table->integer('m_customer_id')->comment('{"src":"m_customer.id"}');
            $table->integer('sektor')->nullable()->comment('{"src":"set.m_general.id"}');
            $table->integer('jenis')->comment('{"src":"set.m_general.id"}');
            $table->boolean('is_active')->default(1);
            $table->integer('ukuran_kontainer')->nullable();
            $table->decimal('tarif_sewa',18,2);
            $table->decimal('tarif_sewa_diskon',18,2);
            // $table->string('tipe_kontainer',50)->nullable();
            $table->text('catatan')->nullable();
            $table->decimal('tarif_ppjk',18,2)->nullable();
            $table->string('tt_elektronik')->nullable();
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