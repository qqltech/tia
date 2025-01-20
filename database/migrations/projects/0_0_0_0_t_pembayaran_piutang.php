<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpembayaranpiutang extends Migration
{
    protected $tableName = "t_pembayaran_piutang";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('no_draft',20)->nullable();
            $table->string('no_pembayaran',20)->nullable();
            $table->string('status',20)->default("DRAFT")->nullable();
            $table->date('tanggal')->nullable();
            $table->date('tanggal_pembayaran');
            $table->integer('tipe_pembayaran')->comment('{"src":"set.m_general.id"}');
            $table->decimal('total_amt', 18, 4)->nullable();
            $table->integer('m_akun_pembayaran_id')->comment('{"src":"m_coa.id"}');
            $table->integer('m_akun_bank_id')->comment('{"src":"m_coa.id"}')->nullable();
            $table->integer('customer')->comment('{"src":"m_customer.id"}');
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