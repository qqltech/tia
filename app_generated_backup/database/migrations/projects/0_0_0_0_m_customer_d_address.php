<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mcustomerdaddress extends Migration
{
    protected $tableName = "m_customer_d_address";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->integer('m_customer_id')->comment('{"fk":"m_customer.id"}');
            $table->string('lokasi_stuff');
            // $table->integer('tipe')->comment('{"src":"m_general.id"}');
            // $table->string('nama');
            // $table->string('telepon',20);
            // $table->string('email',100);
            $table->text('alamat');
            // $table->string('kota', 20);
            // $table->string('fax',20);
            // $table->text('note');
            // $table->boolean('default')->default(true);
            $table->boolean('is_active')->default(true);
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