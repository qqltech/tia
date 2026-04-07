<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tinternald extends Migration
{
    protected $tableName = "t_internal_d";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);

            $table->unsignedBigInteger('t_internal_id')->comment('{"fk": "t_internal.id"}')->nullable();
            $table->unsignedBigInteger('m_item_id')->comment('{"src": "m_item.id"}')->nullable();
            $table->unsignedBigInteger('uom_id')->comment('{"src": "set.m_general.id"}')->nullable();
            $table->decimal('qty_stock', 18, 4)->nullable();

            $table->decimal('usage', 18, 4)->nullable();
            $table->string('catatan')->nullable(); 

            $table->integer('creator_id')->nullable();
            $table->integer('last_editor_id')->nullable();
            $table->timestamps();
            $table->integer('deleted_id')->nullable();
            $table->timestamp('deleted_at')->nullable();
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