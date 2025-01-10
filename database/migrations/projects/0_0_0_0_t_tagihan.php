<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ttagihan extends Migration
{
    protected $tableName = "t_tagihan";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('no_draft')->nullable();
            $table->string('no_tagihan')->nullable();
            $table->integer('no_buku_order')->comment('{"src":"t_buku_order.id"}');
            $table->integer('customer')->comment('{"src":"m_customer.id"}');
            $table->integer('no_faktur_pajak')->comment('{"src":"m_faktur_pajak_d.id"}');
            $table->string('status',10);
            $table->date('tgl');
            $table->string('tipe_tagihan')->nullable();
            $table->decimal('total_amount',18,4);
            $table->decimal('ppn',18,4);
            $table->decimal('grand_total_amount',18,4);
            $table->decimal('piutang',18,4)->nullable();
            // $table->decimal('tarif_coo',18,4);
            // $table->decimal('tarif_ppjk',18,4);
            $table->decimal('grand_total',18,4)->nullable();
            $table->decimal('total_kontainer',18,4)->nullable();
            $table->decimal('total_lain',18,4)->nullable();
            $table->decimal('total_ppn',18,4)->nullable();
            $table->decimal('total_setelah_ppn',18,4)->nullable();
            $table->decimal('total_tarif_jasa',18,4)->nullable();
            $table->text('catatan')->nullable();
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