<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tinternal extends Migration
{
    protected $tableName = "t_internal";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);

            $table->string('no_pemakaian', 100)->nullable();
            $table->string('status')->nullable();
            $table->date('tanggal')->nullable()->default(DB::raw('CURRENT_DATE'));
            $table->bigInteger('m_kary_id')->nullable()->comment('{"src": "set.m_kary.id"}');
            $table->date('filter_tahun')->nullable();
            $table->string('catatan', 100)->nullable();

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