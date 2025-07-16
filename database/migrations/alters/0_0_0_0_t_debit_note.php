<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tdebitnote extends Migration
{
    protected $tableName = "t_debit_note";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->bigInteger('perkiraan_debit')->comment('{"src": "m_coa.id"}')->change();
            // $table->integer('customer_id')->comment('{"src":"m_customer.id"}')->nullable();
            // $table->renameColumn('supplier','supplier_id');
        });
    }
}
