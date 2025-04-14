<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ttagihandtarif extends Migration
{
    protected $tableName = "t_tagihan_d_tarif";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->integer('persentase_konsolidator')->nullable();
            // $table->renameColumn('persentase_konsolidator','persentase_konsolidator_jasa');
            $table->decimal('persentase_konsolidator_jasa', 18,4)->nullable()->change();
        });
    }
}
