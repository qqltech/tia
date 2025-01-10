<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tkomisiundername extends Migration
{
    protected $tableName = "t_komisi_undername";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->renameColumn('presentase','persentase');
            // $table->bigInteger('cutomer_id')->comment('{"src":"m_customer.id"}')->nullable();
            // $table->renameColumn('cutomer_id','customer_id');
            $table->date('tanggal_pelunasan')->nullable();
        });
    }
}
