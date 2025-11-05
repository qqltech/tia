<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpremi extends Migration
{
    protected $tableName = "t_premi";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('no_draft',40)->nullable();
            $table->string('no_premi',40)->nullable();
            $table->integer('t_spk_angkutan_id')->comment('{"src":"t_spk_angkutan.id"}')->nullable();
            $table->integer('grup_head_id')->comment('{"src":"m_grup_head.id"}')->nullable();
            $table->decimal('tol',18,2)->nullable();
            $table->text('catatan')->change();
            $table->string("status", 10)->default("DRAFT")->nullable();
            $table->decimal('total_premi',18,2)->nullable();
            $table->date('tgl')->nullable();
            $table->decimal('premi',18,4);
            
            $table->decimal('hutang_supir',18,4)->nullable();
            $table->decimal('hutang_dibayar',18,4)->nullable();
            $table->decimal('total_premi_diterima',18,4)->nullable();
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