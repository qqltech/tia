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
            $table->string('no_spk');
            $table->string('kode')->nullable();
            $table->integer('lokasi_stuffing')->comment('{"src":"m_lokasistuffing.id"}');
            $table->integer('jenis')->comment('{"src":"set.m_general.id"}');
            $table->float('sangu_tia');
            $table->float('sangu_free');
            $table->string('status',20);
            $table->float('tarif_genzet');
            $table->float('ganti_solar_sangu');
            $table->float('ganti_solar_tag');
            $table->integer('kilometer');
            $table->integer('buku_order_id')->comment('{"src":"t_buku_order.id"}');
            $table->text('catatan')->nullable();
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