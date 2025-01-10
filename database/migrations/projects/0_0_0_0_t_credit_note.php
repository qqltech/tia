<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tcreditnote extends Migration
{
    protected $tableName = "t_credit_note";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('no_draft')->nullable();
            $table->string('no_credit_note',20)->nullable();
            $table->integer('tipe_credit_note')->comment('{"src":"set.m_general.id"}');
            $table->date('tanggal')->nullable();
            $table->integer('supplier_id')->comment('{"src":"m_supplier.id"}');
            $table->integer('customer_id')->comment('{"src":"m_customer.id"}')->nullable();
            $table->integer('perkiraan_credit')->comment('{"src":"m_coa.id"}')->nullable();
            $table->decimal('total_credit_note',18,4)->nullable();
            $table->text('catatan')->nullable();
            $table->string('status',40)->default("DRAFT")->nullable();

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