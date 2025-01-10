<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpurchaseinvoice extends Migration
{
    protected $tableName = "t_purchase_invoice";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            // $table->integer('tipe_pembayaran_id')->comment('{"src":"set.general.id"}')->change();
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
        });
    }
}
