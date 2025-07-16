<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tsubcreditnote extends Migration
{
    protected $tableName = "t_sub_credit_note";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->integer('t_tagihan_id')->comment('{"src":"t_tagihan.id"}')->nullable();
            // $table->integer('t_purchase_invoice_id')->comment('{"src":"t_purchase_invoice.id"}')->nullable();
            // $table->integer('tipe_perkiraan')->comment('{"src":"set.m_general.id"}')->nullable();
        });
    }
}
