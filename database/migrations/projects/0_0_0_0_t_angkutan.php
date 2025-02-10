<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tangkutan extends Migration
{
    protected $tableName = "t_angkutan";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string("no_draft",20)->nullable();
            $table->integer("t_buku_order_id")->comment('{"src":"t_buku_order.id"}')->nullable();
            $table->string("no_angkutan")->nullable();
            $table->string("status", 10)->default("DRAFT")->nullable();
            $table->boolean("pph")->nullable();
            $table->date("tanggal")->nullable();
            $table->string("party")->nullable();
            $table->boolean('custom_stuple')->nullable()->default(0);
            
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
