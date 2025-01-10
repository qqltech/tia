<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tppjk extends Migration
{
    protected $tableName = "t_ppjk";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('no_draft',20)->nullable();
            $table->string('no_ppjk',20)->nullable();
            $table->integer('t_buku_order_id')->comment('{"src":"t_buku_order.id"}');
            $table->string('status',10)->default("DRAFT")->nullable();
            $table->date('tanggal')->nullable();
            $table->integer('m_customer_id')->comment('{"src":"m_customer.id"}');
            $table->string('kode_customer')->nullable();
            $table->string('no_npwp')->nullable();
            $table->string('no_peb_pib',20)->nullable();
            $table->date('tanggal_peb_pib')->nullable();
            $table->string('no_sppb',20)->nullable();
            $table->date('tanggal_sppb')->nullable();
            $table->decimal('invoice',18,4)->nullable();
            $table->decimal('ppn_pib',18,4)->nullable();
            $table->string('currency',20)->nullable();
            $table->decimal('nilai_kurs',18,4)->nullable();
            $table->text('catatan')->nullable();
            //penting
            $table->integer("creator_id")->nullable();
            $table->integer("last_editor_id")->nullable();
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