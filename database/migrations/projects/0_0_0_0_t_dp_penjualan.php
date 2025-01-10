<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tdppenjualan extends Migration
{
    protected $tableName = "t_dp_penjualan";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);

            $table->string('no_dp',40)->nullable();
            $table->string('no_draft',40)->nullable();
            $table->bigInteger('m_customer_id')->comment('{"src": "m_customer.id"}')->nullable();
            $table->bigInteger('t_buku_order_id')->comment('{"src": "t_buku_order.id"}')->nullable();
            $table->date('tgl_dp');
            $table->bigInteger('tipe_dp_id')->comment('{"src": "set.m_general.id"}')->nullable();
            $table->decimal('total_amount',18,4);
            $table->string("status", 10)->default("DRAFT")->nullable();
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