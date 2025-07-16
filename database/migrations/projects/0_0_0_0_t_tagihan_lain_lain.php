<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ttagihanlainlain extends Migration
{
    protected $tableName = "t_tagihan_lain_lain";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('no_draft',40);
            $table->string('no_tagihan_lain_lain',200);
            $table->integer('no_buku_order')->comment('{"src":"t_buku_order.id"}');
            $table->integer('customer')->comment('{"src":"m_customer.id"}');
            $table->string('status',10);
            $table->datetime('tgl');
            $table->datetime('tgl_nota');
            $table->decimal('ppn',18,4);
            $table->decimal('total_amount_ppn',18,4);
            $table->decimal('total_amount_non_ppn',18,4);
            $table->decimal('total_ppn',18,4);
            $table->decimal('grand_total_amount',18,4);
            $table->decimal('piutang',18,4);
            $table->string('catatan',500);
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