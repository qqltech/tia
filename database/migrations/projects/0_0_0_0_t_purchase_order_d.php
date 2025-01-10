<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpurchaseorderd extends Migration
{
    protected $tableName = "t_purchase_order_d";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->integer('t_purchase_order_id')->comment('{"fk":"t_purchase_order.id"}')->nullable();
            $table->integer('m_item_id')->comment('{"src":"m_item.id"}');
            $table->integer('quantity');
            $table->decimal('harga',18,4);
            $table->decimal('disc1',18,4)->nullable();
            $table->decimal('disc2',18,4)->nullable();
            $table->decimal('disc_amt',18,4)->nullable();
            $table->boolean('is_bundling')->default(0)->nullable();
            $table->string('catatan',100)->nullable();
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