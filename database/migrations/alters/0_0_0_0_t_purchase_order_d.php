<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpurchaseorderd extends Migration
{
    protected $tableName = "t_purchase_order_d";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->boolean('is_bundling')->change();
            // $table->boolean('is_bundling')->nullable()->default(0)->change();
            //$table->dropColumn([ ]);
            // $table->string('catatan',100)->nullable()->change();
            // $table->renameColumn('t_no_po_id','t_purchase_order_id');
        });
    }
}
