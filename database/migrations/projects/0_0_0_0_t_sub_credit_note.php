<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tsubcreditnote extends Migration
{
    protected $tableName = "t_sub_credit_note";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->integer('t_credit_note_d_id')->comment('{"fk":"t_credit_note_d.id"}')->nullable();
            $table->integer('no_urut');
            $table->integer('m_coa_id')->comment('{"src":"m_coa.id"}');
            $table->decimal('amount',18,4);
            $table->integer('t_tagihan_id')->comment('{"src":"t_tagihan.id"}')->nullable();
            $table->integer('t_purchase_invoice_id')->comment('{"src":"t_purchase_invoice.id"}')->nullable();
            $table->integer('tipe_perkiraan')->comment('{"src":"set.m_general.id"}')->nullable();
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