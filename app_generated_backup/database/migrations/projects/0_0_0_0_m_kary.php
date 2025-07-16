<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mkary extends Migration
{
    protected $tableName = "set.m_kary";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('nip',20)->nullable();	           
            $table->string('nama', 100);
            $table->string('no_id', 100);
            $table->string('alamat_ktp', 100);
            $table->string('alamat_domisili', 100);
            $table->string('rt',10);
            $table->string('rw',10);
            $table->string('kota_lahir', 20);
            $table->date('tgl_lahir');
            $table->string('agama', 20);
            $table->string('no_tlp', 20);
            $table->boolean('is_active')->default(true);
            $table->string('divisi', 20);
            $table->string('jenis_kelamin',10);
            $table->string('kota_ktp', 20);
            $table->string('kota_domisili', 20);
            $table->string('kecamatan', 20);
            $table->string('status_perkawinan', 30);
            $table->date('tgl_mulai')->nullable();
            $table->string('email',100);
            $table->text('catatan')->nullable();
            $table->string('foto_kary')->nullable();
            $table->string('foto_ktp')->nullable();
            $table->string('foto_kk')->nullable();
            $table->string('foto_bpjs_ks')->nullable();
            $table->string('foto_bpjs_ktj')->nullable();
            $table->string('no_rek')->nullable(false);
            $table->integer('bank_id')->comment('{"src":"set.m_general.id"}')->nullable(false);
            $table->integer('creator_id')->nullable();
            $table->integer('last_editor_id')->nullable();
            $table->timestamps();
            $table->integer('delete_id')->nullable();
            $table->timestamp('delete_at')->nullable();
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