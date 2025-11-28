<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpurchaseorder extends Migration
{
    protected $tableName = "t_purchase_order";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->decimal('total_amount',18,4)->change();
            // $table->decimal('dpp',18,4)->change();
            // $table->decimal('total_ppn',18,4)->change();
            // $table->decimal('grand_total',18,4)->change();
            // $table->string('ppn',30)->change();
            // $table->bigInteger('ppn')->comment('{"src":"set.m_general.id"}')->nullable()->change();
            // \DB::statement('alter table t_purchase_order alter column ppn type bigint using ppn::bigint');
            // $table->bigInteger('tipe_po')->comment('{"src":"set.m_business_unit.id"}')->nullable();
            // $table->decimal('ppn_persen',18,4)->nullable();
        });
    }
}
