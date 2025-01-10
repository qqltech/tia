<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mtarifangkutan extends Migration
{
    protected $tableName = "m_tarif_angkutan";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);

            $table->string('kode',20)->nullable();
            $table->integer('m_supplier_id')->comment('{"src":"m_supplier.id"}');
            $table->decimal('tarif',18,4);
            $table->decimal('tarif_pengawalan',18,4)->nullable();
            $table->boolean('is_active')->default(1)->nullable();
            $table->integer('sektor')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->integer('ukuran')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->integer('jenis')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->decimal('tarif_stapel',18,4)->nullable();
            $table->integer('jenis_pajak')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->decimal('persen_pajak',12,2)->nullable();
            $table->boolean('kena_pajak')->default(0)->nullable();
            $table->text('catatan')->nullable();
            //penting
            $table->integer("creator_id")->nullable();
            $table->integer("last_editor_id")->nullable();
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