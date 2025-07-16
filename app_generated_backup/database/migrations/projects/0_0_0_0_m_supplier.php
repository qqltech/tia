<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class msupplier extends Migration
{
    protected $tableName = "m_supplier";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('kode')->unique()->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('nama', 100);
            $table->bigInteger('tipe_id')->comment('{"src": "set.m_general.id"}');
            $table->bigInteger('jenis_id')->comment('{"src": "set.m_general.id"}');
            $table->string('pajak', 20)->nullable();
            $table->integer('top');
            $table->string('nik', 20)->nullable();
            $table->string('npwp', 20)->nullable();
            $table->string('alamat', 500);
            $table->boolean('pph')->default(0);
            $table->boolean('b2b')->default(0);
            $table->string('link_b2b', 200)->nullable();
            $table->string('negara', 120); 
            $table->string('provinsi', 120);
            $table->string('kota', 120);
            $table->string('kecamatan', 120);
            $table->integer('bank')->comment('{"src":"set.m_general.id"}');
            $table->string('kode_bank', 10);
            $table->string('no_rekening', 20);
            $table->string('nama_rekening', 100);
            $table->string('no_telp1', 20)->nullable();
            $table->string('no_telp2', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('contact_person1', 100)->nullable();
            $table->string('no_telp_contact_person1', 20)->nullable();
            $table->string('email_contact_person1', 100)->nullable();
            $table->string('contact_person2', 100)->nullable();
            $table->string('email_contact_person2', 100)->nullable();
            $table->string('no_telp_contact_person2', 20)->nullable();
            $table->text('catatan')->nullable();
            
            $table->integer('creator_id')->nullable();
            $table->integer('last_editor_id')->nullable();
            $table->integer('deleted_id')->nullable();
            $table->timestamp('deleted_at')->nullable();
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