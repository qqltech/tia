<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpurchaseinvoiced extends Migration
{
    protected $tableName = "t_purchase_invoice_d";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            // $table->text('catatan')->nullable()->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
        });
    }
}
