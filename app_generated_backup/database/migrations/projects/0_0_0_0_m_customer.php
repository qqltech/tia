<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mcustomer extends Migration
{
    protected $tableName = "m_customer";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->integer('m_customer_group_id')->comment('{"src":"m_customer_group.id"}');
            $table->string('kode')->nullable();
            // $table->integer('m_lokasi_stuffing_id')->comment('{"src":"m_lokasistuffing.id"}');
            $table->string('kota', 50);
            $table->string('kodepos', 10);       
            $table->boolean('taxable')->default(true);
            $table->decimal('coa_piutang')->comment('{"src":"m_customer_group.id"}');
            $table->string('no_tlp2',20)->nullable();
            $table->string('fax1',20)->nullable();
            $table->string('email',100)->nullable();
            $table->string('cp1',100)->nullable();
            $table->string('email_cp1',100)->nullable();
            $table->string('no_tlp_cp2',20)->nullable();
            $table->text('catatan')->nullable();
            $table->float('latitude')->nullable();
            $table->boolean('is_active',10);
            $table->string('jenis_perusahaan');
            $table->string('nama_perusahaan');
            $table->text('alamat')->nullable();
            $table->string('kecamatan', 20);
            $table->integer('top');
            $table->integer('tolerance');
            $table->string('no_tlp1',20)->nullable();
            $table->string('no_tlp3',20)->nullable();
            $table->string('fax2',20)->nullable();
            $table->text('website')->nullable();
            $table->string('no_tlp_cp1',20)->nullable();
            $table->string('cp2',100)->nullable();
            $table->string('email_cp2',100)->nullable();
            $table->float('longtitude')->nullable();
            $table->integer('jabatan1')->comment('{"src":"set.m_general.id"}');
            $table->integer('jabatan2')->comment('{"src":"set.m_general.id"}');
            $table->boolean("custom_stuple")->default(0)->nullable();
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