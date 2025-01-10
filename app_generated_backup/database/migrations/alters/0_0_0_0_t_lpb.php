<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tlpb extends Migration
{
    protected $tableName = "t_lpb";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            // $table->bigInteger('m_supplier_id')->comment('{"src":"m_supplier.id"}')->nullable();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
        });
    }
}
