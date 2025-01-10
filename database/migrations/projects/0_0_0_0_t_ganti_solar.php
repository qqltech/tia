<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tgantisolar extends Migration
{
    protected $tableName = "t_ganti_solar";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->integer('t_spk_angkutan_id')->comment('{"src":"t_spk_angkutan.id"}');
            $table->string('status')->nullable();
            // $table->integer('tipe')->comment('{"src":"set.m_general.id"}');
            $table->date('tgl')->nullable();
            $table->string('no_container_1');
            $table->string('no_container_2');
            // $table->string('rit')->nullable();
            // $table->integer('supir')->comment('{"src":"set.m_kary.id"}');
            // $table->string('dari')->nullable();
            // $table->string('ke')->nullable();
            // $table->integer('sektor')->comment('{"src":"set.m_general.id"}')->nullable();
            // $table->decimal('premi',18,4)->nullable();
            // $table->decimal('sangu',18,4)->nullable();
            $table->decimal('nominal',18,4)->nullable(); //sangu - premi = nominal
            // $table->text('catatan')->nullable();
            
            

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