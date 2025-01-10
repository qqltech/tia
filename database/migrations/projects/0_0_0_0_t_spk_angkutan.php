<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tspkangkutan extends Migration
{
    protected $tableName = "t_spk_angkutan";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('no_spk', 50)->nullable();
            $table->integer('tipe_spk')->comment('{"src":"set.m_general.id"}');
            $table->integer('depo')->comment('{"src":"set.m_general.id"}');
            $table->string('status');
            $table->integer('t_buku_order_1_id')->comment('{"src":"t_buku_order.id"}');
            $table->integer('t_detail_npwp_container_1_id')->comment('{"src":"t_buku_order_d_npwp.id"}');
            $table->integer('isi_container_1')->comment('{"src":"set.m_general.id"}');
            $table->string('no_container_1')->nullable();
            
            
            $table->integer('t_buku_order_2_id')->comment('{"src":"t_buku_order.id"}')->nullable();
            $table->integer('t_detail_npwp_container_2_id')->comment('{"src":"t_buku_order_d_npwp.id"}')->nullable();
            $table->integer('isi_container_2')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->string('no_container_2')->nullable();
            $table->integer('trip_id')->comment('{"src":"set.m_general.id"}')->nullable();

            $table->date('tanggal_spk');
            $table->integer('supir')->comment('{"src":"set.m_kary.id"}');
            $table->integer('sektor1')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->integer('head')->comment('{"src":"set.m_general.id"}');
            $table->integer('chasis')->comment('{"src":"set.m_general.id"}');
            $table->integer('chasis2')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->string('dari', 50);
            $table->string('ke', 10);
            $table->decimal('sangu',18,2)->nullable();
            $table->decimal('total_sangu',18,2)->nullable();
            $table->date('tanggal_out');
            $table->string('waktu_out',10);
            $table->date('tanggal_in');
            $table->string('waktu_in',10);
            $table->text('catatan')->nullable();
            $table->integer('m_supplier_id')->comment('{"src":"m_supplier.id"}')->nullable();
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