<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mtarifkomisi extends Migration
{
    protected $tableName = "m_tarif_komisi";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('kode', 20);
            $table->boolean('is_active')->default(1)->nullable();
            $table->boolean('is_container_tarif_20')->default(0);
            $table->decimal('container_tarif_20', 18, 4)->nullable();
            $table->boolean('is_container_tarif_40')->default(0);
            $table->decimal('container_tarif_40', 18, 4)->nullable();
            $table->boolean('is_tarif_dokumen')->default(0);
            $table->decimal('tarif_dokumen', 18, 4)->nullable();
            $table->boolean('is_tarif_order')->default(0);
            $table->decimal('tarif_order', 18, 4)->nullable();
            $table->boolean('is_invoice_minimal')->default(0);
            $table->decimal('invoice_minimal', 18, 4)->nullable();
            $table->decimal('tarif_umkm', 18, 4)->nullable();
            $table->decimal('selisih_ppn_pph', 18, 4)->nullable();
            $table->decimal('kurs', 18, 4)->nullable();
            $table->text('catatan')->nullable();
            $table->string('tipe_komisi')->nullable();
            $table->bigInteger('m_customer_id')->comment('{"src":"m_customer.id"}')->nullable();
            $table->bigInteger('tipe_order')->comment('{"src":"set.m_general.id"}')->nullable();

            $table->integer("creator_id")->nullable();
            $table->integer("last_editor_id")->nullable();
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