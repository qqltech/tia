<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tkomisiundername extends Migration
{
    protected $tableName = "t_komisi_undername";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('no_komisi_undername',50)->nullable();
            $table->date('tanggal');
            $table->integer('t_buku_order_id')->comment('{"src":"t_buku_order.id"}');
            $table->string('tipe_komisi',50);
            $table->decimal('nilai_invoice',18,4);
            $table->decimal('kurs',18,4);
            $table->decimal('nilai_pabean',18,4);
            $table->decimal('nilai_pajak_komisi',18,4);
            $table->decimal('tarif_komisi',18,4);
            $table->decimal('total_komisi',18,4);
            $table->decimal('persentase',18,4);
            $table->string('status_id', 20)->nullable();
            $table->text('catatan')->nullable();
            $table->bigInteger('customer_id')->comment('{"src":"m_customer.id"}')->nullable();
            $table->date('tanggal_pelunasan')->nullable();
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