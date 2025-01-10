<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tnotarampung extends Migration
{
    protected $tableName = "t_nota_rampung";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('no_draft',20)->nullable();
            $table->string('no_nota_rampung',20)->nullable();
            $table->integer('t_buku_order_id')->comment('{"src":"t_buku_order.id"}');
            $table->string('status',10)->default("DRAFT")->nullable();
            $table->date('tanggal')->nullable();
            $table->string('customer')->nullable();
            $table->string('pelabuhan')->nullable();
            $table->integer('container1')->nullable()->comment('{"src":"set.m_general.id"}');
            $table->integer('container2')->nullable()->comment('{"src":"set.m_general.id"}');
            $table->integer('tipe1')->nullable()->comment('{"src":"set.m_general.id"}');
            $table->integer('tipe2')->nullable()->comment('{"src":"set.m_general.id"}');
            $table->decimal('vgm',18,4)->nullable();
            $table->decimal('lolo_non_sp',18,4)->nullable();
            // $table->string('currency',20)->nullable();
            // $table->float('kurs')->default(1);
            $table->string("foto_scn")->nullable();
            $table->text('catatan')->nullable();
            $table->string('no_stack',50)->nullable();
            $table->date('tgl_stack')->nullable();
            $table->string('no_eir',50)->nullable();
            $table->date('tgl_eir')->nullable();
            $table->decimal('grand_total',18,4)->default(0);
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
