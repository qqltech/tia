<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mtarifnotarampung extends Migration
{
    protected $tableName = "m_tarif_nota_rampung";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('no_tarif',30)->nullable();
            $table->boolean('is_active')->default(1)->nullable();
            $table->integer('kode_pelabuhan')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->integer('ukuran_container')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->integer('jenis_container')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->integer('tipe_tarif')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->decimal('tarif_lolo',18,4)->nullable()->default(0);
            $table->decimal('tarif_m2',18,4)->nullable()->default(0);
            $table->decimal('tarif_m3',18,4)->nullable()->default(0);
            $table->decimal('tarif_m4',18,4)->nullable()->default(0);
            $table->decimal('tarif_m5',18,4)->nullable()->default(0);
            $table->decimal('tarif_ow',18,4)->nullable()->default(0);
            $table->decimal('tarif_plg_mon',18,4)->nullable()->default(0);
            $table->decimal('tarif_ge',18,4)->nullable()->default(0);
            $table->decimal('tarif_container_doc',18,4)->nullable()->default(0);
            $table->decimal('tarif_strtp_stuff',18,4)->nullable()->default(0);
            $table->decimal('tarif_batal_muat_pindah',18,4)->nullable()->default(0);
            $table->decimal('tarif_closing_container',18,4)->nullable()->default(0);
            $table->text('catatan')->nullable();
            
            $table->decimal('tarif_mob',18,4)->nullable()->default(0);
            $table->decimal('tarif_vgm',18,4)->nullable()->default(0);
            $table->decimal('tarif_by_adm_nr',18,4)->nullable()->default(0);
            $table->decimal('tarif_materai',18,4)->nullable()->default(0);
            $table->decimal('tarif_denda_koreksi',18,4)->nullable()->default(0);
            $table->decimal('tarif_denda_sp',18,4)->nullable()->default(0);
            $table->decimal('tarif_behandle',18,4)->nullable()->default(0);

            $table->integer('tipe_tarif')->comment('{"src":"set.m_general.id"}')->nullable();


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