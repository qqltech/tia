<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tbukupenyesuaian extends Migration
{
    protected $tableName = "t_buku_penyesuaian";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('no_buku_penyesuaian',40)->nullable();
            $table->string('no_draft',40)->nullable();
            $table->bigInteger('t_buku_order_id')->comment('{"src": "t_buku_order.id"}')->nullable();
            $table->date('tanggal_buku_penyesuaian');
            $table->bigInteger('no_bkk_id')->comment('{"src": "t_bkk.id"}')->nullable();
            $table->decimal('total_amt',18,4);
            $table->bigInteger('m_akun_pembayaran_id')->comment('{"src": "m_coa.id"}')->nullable();
            $table->string("status", 10)->default("DRAFT")->nullable();
            $table->text('keterangan')->nullable();
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