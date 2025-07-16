<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tconfirmasset extends Migration
{
    protected $tableName = "t_confirm_asset";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->integer('t_lpb_id')->comment('{"src":"t_lpb.id"}');
            $table->integer('pic')->comment('{"src":"set.m_kary.id"}');
            $table->date('tgl_asset');
            $table->integer('masa_manfaat');
            $table->integer('m_item_id')->comment('{"src":"m_item.id"}');
            $table->integer('m_perkiraan_akun_penyusutan')->comment('{"src":"m_coa.id"}');
            $table->decimal('harga_perolehan',18,2);
            $table->integer('kategori_id')->comment('{"src":"set.m_general.id"}');
            $table->integer('m_perkiraan_asset_id')->comment('{"src":"m_coa.id"}');
            $table->integer('m_perkiraan_by_akun_penyusutan')->comment('{"src":"m_coa.id"}');
            $table->text('catatan')->nullable();
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