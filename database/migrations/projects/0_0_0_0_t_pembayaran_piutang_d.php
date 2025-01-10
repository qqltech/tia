<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpembayaranpiutangd extends Migration
{
    protected $tableName = "t_pembayaran_piutang_d";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->bigInteger('t_pembayaran_piutang_id')->comment('{"fk":"t_pembayaran_piutang.id"}')->nullable();
            $table->bigInteger('t_tagihan_id')->comment('{"src":"t_tagihan.id"}');
            $table->decimal('bayar', 18, 4)->nullable();
            $table->decimal('sisa_piutang', 18, 4)->nullable();
            $table->decimal('total_bayar', 18, 4)->nullable();
            $table->text('catatan')->nullable();
            $table->integer('creator_id')->nullable();
            $table->integer('last_editor_id')->nullable();
            $table->integer('delete_id')->nullable();
            $table->timestamp('delete_at')->nullable();
            $table->bigInteger('pph_id')->nullable()->comment('{"src":"set.m_general.id"}');
            $table->text('bukti_potong')->nullable();
            $table->decimal('total_pph',18,4)->nullable();
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