<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tbkm extends Migration
{
    protected $tableName = "t_bkm";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('no_bkm', 20)->nullable();
            $table->string('no_draft', 20)->nullable();
            $table->string('status');
            $table->integer('t_buku_order_id')->comment('{"src":"t_buku_order.id"}');
            $table->date('tanggal');
            // $table->integer('m_coa_id')->comment('{"src":"m_coa.id"}');
            $table->decimal('total_amt', 18, 4);
            $table->integer('m_akun_pembayaran_id')->comment('{"src":"m_coa.id"}');
            $table->integer('tipe_pembayaran')->comment('{"src":"set.m_general.id"}');
            $table->integer('m_akun_bank_id')->comment('{"src":"m_coa.id"}')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('m_business_unit_id')->comment('{"src":"set.m_business_unit.id"}');

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