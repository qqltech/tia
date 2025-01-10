<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tkomisi extends Migration
{
    protected $tableName = "t_komisi";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('tipe_komisi', 50);
            $table->string('no_komisi', 50)->nullable();
            $table->integer('m_tarif_komisi_id')->comment('{"src":"m_tarif_komisi.id"}');
            $table->integer('m_customer_id')->comment('{"src":"m_customer.id"}');
            $table->integer('t_buku_order_awal_id')->comment('{"src":"t_buku_order.id"}');
            $table->integer('t_buku_order_akhir_id')->comment('{"src":"t_buku_order.id"}');
            $table->boolean('is_pph')->default(false);
            $table->decimal('grandtotal',18,4)->default(0)->nullable();
            $table->string('status', 20)->nullable();
            $table->text('catatan')->nullable();

            $table->integer('creator_id')->nullable();
            $table->integer('last_editor_id')->nullable();
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