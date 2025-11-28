<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpurchaseorder extends Migration
{
    protected $tableName = "t_purchase_order";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('no_draft',20)->nullable();
            $table->string('no_po',20)->nullable();
            $table->date('tanggal')->nullable();
            $table->integer('t_purchase_order_id')->nullable();
            $table->integer('m_supplier_id')->comment('{"src":"m_supplier.id"}')->nullable();
            $table->text('b2b_link')->nullable();
            $table->date('estimasi_kedatangan');
            $table->text('catatan')->nullable();
            $table->text('alasan_revisi')->nullable();
            $table->string('status',20)->default('DRAFT')->nullable();
            $table->string('tipe',20)->nullable();
            $table->integer('termin')->comment('{"src":"set.m_general.id"}');
            $table->string('ppn',30);
            $table->decimal('ppn_persen',18,4)->nullable();
            $table->decimal('total_amount',18,4);
            $table->decimal('dpp',18,4);
            $table->decimal('total_ppn',18,4);
            $table->decimal('grand_total',18,4);
            $table->bigInteger('tipe_po')->comment('{"src":"set.m_business_unit.id"}')->nullable();

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