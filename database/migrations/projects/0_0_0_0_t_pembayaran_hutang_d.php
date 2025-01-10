<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpembayaranhutangd extends Migration
{
    protected $tableName = "t_pembayaran_hutang_d";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->integer('t_pembayaran_hutang_id')->comment('{"fk":"t_pembayaran_hutang.id"}')->nullable();
            $table->integer('t_purchase_invoice_id')->comment('{"src":"t_purchase_invoice.id"}');
            // $table->integer('t_rencana_pembayaran_hutang_id')->comment('{"src":"t_rencana_pembayaran_hutang.id"}')->nullable();
            $table->integer('t_jurnal_angkutan_id')->comment('{"src":"t_jurnal_angkutan.id"}')->nullable();   
            $table->decimal('total_bayar',18,4);
            $table->text('keterangan')->nullable();
            $table->string('no_pi')->nullable();
            $table->date('tgl_pi')->nullable();
            $table->date('tgl_jt')->nullable();
            $table->decimal('nilai_hutang',18,4)->nullable();
            $table->decimal('sisa_hutang',18,4)->nullable();
            $table->decimal('bayar',18,4)->nullable();
            
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