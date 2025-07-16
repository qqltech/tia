<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class trencanapembayaranhutang extends Migration
{
    protected $tableName = "t_rencana_pembayaran_hutang";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->string('status')->nullable()->change();
            // $table->integer('m_supplier_id')->comment('{"src":"m_supplier.id"}')->nullable();
            // $table->string('status')->default("DRAFT");

        });
    }
}
