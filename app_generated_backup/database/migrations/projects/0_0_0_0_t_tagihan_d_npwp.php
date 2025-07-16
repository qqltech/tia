<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ttagihandnpwp extends Migration
{
    protected $tableName = "t_tagihan_d_npwp";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->integer('t_buku_order_id')->comment('{"src":"t_buku_order.id"}')->nullable();
            $table->integer('t_tagihan_id')->comment('{"fk":"t_tagihan.id"}')->nullable();
            $table->string('no_prefix', 20);
            $table->string('no_suffix', 20);
            $table->integer('ukuran')->comment('{"src":"set.m_general.id"}');
            $table->integer('tipe')->comment('{"src":"set.m_general.id"}');
            $table->integer('jenis')->comment('{"src":"set.m_general.id"}');
            $table->integer('sektor')->comment('{"src":"set.m_general.id"}');
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