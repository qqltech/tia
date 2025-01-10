<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpembayaranhutang extends Migration
{
    protected $tableName = "t_pembayaran_hutang";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('no_draft',20)->nullable();
            $table->string('no_pembayaran',20)->nullable();
            $table->string('status',40)->default('DRAFT')->nullable();
            $table->date('tanggal')->nullable();
            $table->date('tanggal_pembayaran');
            $table->integer('t_rencana_pembayaran_hutang_id')->comment('{"src":"t_rencana_pembayaran_hutang.id"}')->nullable();
            $table->integer('tipe_pembayaran_id')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->decimal('total_amt',18,4)->nullable();
            $table->boolean('include_pph');
            $table->integer('m_akun_pembayaran_id')->comment('{"src":"m_coa.id"}');
            $table->integer('supplier_id')->comment('{"src":"m_supplier.id"}');
            $table->text('keterangan')->nullable();

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