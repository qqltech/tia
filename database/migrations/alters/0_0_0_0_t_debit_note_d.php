<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tdebitnoted extends Migration
{
    protected $tableName = "t_debit_note_d";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->integer('t_purchase_invoice_id')->comment('{"src":"t_purchase_invoice.id"}')->nullable();
            // $table->integer('no_urut');
            // $table->decimal('sub_total_amount',18,4);
            // $table->decimal('sub_total_amount',18,4)->nullable()->change();
        });
    }
}
