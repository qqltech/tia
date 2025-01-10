<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tbabukuorder extends Migration
{
    protected $tableName = "t_ba_buku_order";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->bigInteger('t_buku_order_id')->comment('{"src":"t_buku_order.id}')->change();
        });
    }
}
