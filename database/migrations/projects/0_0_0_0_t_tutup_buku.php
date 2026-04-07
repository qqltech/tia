<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ttutupbuku extends Migration
{
    protected $tableName = "t_tutup_buku";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);

            $table->bigInteger('m_bu_id')->comment('{"src":"set.m_business_unit.id"}');

            $table->string('grup',100);
            $table->string('periode',100);
            $table->date('open_date');
            $table->datetime('open_time');
            $table->date('close_date')->nullable();
            $table->datetime('close_time')->nullable();

            $table->string('status', 20)->default('POST')->nullable();
            $table->text('note')->nullable();

            // penting
            $table->bigInteger('creator_id')->comment('{"src":"default_users.id"}')->nullable();
            $table->bigInteger('last_editor_id')->comment('{"src":"default_users.id"}')->nullable();
            $table->timestamps();
            $table->bigInteger('deletor_id')->nullable();
            $table->datetime('deleted_at')->nullable();
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