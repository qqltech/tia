<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tbondinasluar extends Migration
{
    protected $tableName = "t_bon_dinas_luar";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('no_bon_dinas_luar', 20)->nullable();
            $table->string('no_draft', 20)->nullable();
            $table->string('status', 100)->nullable();
            $table->bigInteger('tipe_order_id')->comment('{"src":"m_gen.id"}');
            $table->bigInteger('tipe_kategori_id')->comment('{"src":"m_coa.id"}');
            $table->date('tanggal')->nullable();
            $table->bigInteger('t_bkk_id')->comment('{"src":"t_bkk.id"}');
            $table->string('no_bkk', 100);
            $table->decimal('total_amt', 18, 4);
            $table->bigInteger('m_kary_id')->comment('{"src":"set.m_kary.id"}');
            $table->bigInteger('m_supplier_id')->comment('{"src":"m_supplier.id"}');
            $table->integer('m_akun_bank_id')->comment('{"src":"m_coa.id"}')->nullable();
            $table->text('catatan')->nullable();

            $table->bigInteger('creator_id')->nullable();
            $table->bigInteger('last_editor_id')->nullable();
            $table->bigInteger('deleted_id')->nullable();
            $table->timestamp('deleted_at')->nullable();
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