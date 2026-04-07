<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tassetconfirmationkendaraan extends Migration
{
    protected $tableName = "t_asset_confirmation_kendaraan";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);

            $table->integer('t_confirm_asset_id')->comment('{"fk":"t_confirm_asset.id"}')->nullable();
            $table->bigInteger('jenis_kendaraan_id')->comment('{"src": "set.m_general.id"}');
            $table->string('no_mesin', 250);
            $table->string('no_rangka', 250);
            $table->string('nopol', 250);
            $table->string('no_bpkb', 250);
            $table->string('tahun_produksi', 10);
            $table->bigInteger('merk_id')->comment('{"src": "set.m_general.id"}');
            $table->integer('jumlah_roda');
            $table->bigInteger('bahan_bakar_id')->comment('{"src": "set.m_general.id"}');
            $table->integer('jumlah_cylinder');
            $table->bigInteger('warna_id')->comment('{"src": "set.m_general.id"}');
            $table->string('no_faktur', 250);
            $table->date('tanggal_faktur');
            $table->string('nama_pemilik', 250);
            $table->string('no_urut_kendaraan')->nullable();

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