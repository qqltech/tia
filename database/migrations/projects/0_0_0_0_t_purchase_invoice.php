<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpurchaseinvoice extends Migration
{
    protected $tableName = "t_purchase_invoice";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);

            $table->string('no_draft', 20);
            $table->string('status', 20)->default('DRAFT');
            $table->date('tanggal');
            $table->string('no_pi', 20);
            $table->integer('t_po_id')->comment('{"src":"t_purchase_order.id"}');
            $table->integer('m_faktur_pajak_d_id')->comment('{"src":"m_faktur_pajak_d.id"}');

            $table->integer('tipe_pembayaran_id')->comment('{"src":"set.m_general.id"}')->nullable();
            $table->integer('m_supplier_id')->comment('{"src":"m_supplier.id"}');
            $table->integer('t_lpb_id')->comment('{"src":"t_lpb.id"}');

            $table->integer('jenis_ppn')->comment('{"src":"set.m_general.id"}');
            $table->float('persen_ppn');
            $table->integer('jenis_pph')->comment('{"src":"set.m_general.id"}');
            $table->float('persen_pph');
            $table->text('catatan')->nullable();

            $table->decimal('total_pph', 18, 4);
            $table->decimal('total_ppn', 18, 4);
            $table->decimal('grand_total', 18, 4);
            $table->decimal('utang',18,4)->nullable();

            $table->integer('creator_id')->nullable();
            $table->integer('last_editor_id')->nullable();
            $table->integer('deleted_id')->nullable();
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