<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tcreditnote extends Migration
{
    protected $tableName = "t_credit_note";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->renameColumn('supplier','supplier_id');
            // $table->string('no_draft')->nullable();
            // $table->integer('supplier_id')->comment('{"src":"m_supplier.id"}')->nullable()->change();
            // $table->integer('customer_id')->comment('{"src":"m_customer.id"}')->nullable();
            // $table->integer('perkiraan_credit')->comment('{"src":"m_coa.id"}')->nullable()->change();
            // $table->decimal('total_credit_note',18,4)->nullable()->change();
        });
    }
}
