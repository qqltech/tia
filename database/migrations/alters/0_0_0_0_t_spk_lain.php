<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tspklain extends Migration
{
    protected $tableName = "t_spk_lain";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->integer('buku_order_id')->comment('{"src":"t_buku_order.id"}')->nullable();
            // $table->integer('t_buku_order_id')->comment('{"src":"t_buku_order.id"}')->nullable();
            // $table->integer('m_customer_id')->comment('{"src":"m_customer.id"}')->nullable();
            // $table->integer('ukuran')->comment('{"src":"set.m_general.id"}')->nullable();
        });
    }
}
