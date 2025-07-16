<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tjurnalangkutan extends Migration
{
    protected $tableName = "t_jurnal_angkutan";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['kode_supplier']);
            // $table->decimal('grand_total',18,4)->nullable();
            // $table->decimal('ppn',18,4)->nullable();
            // $table->decimal('dpp',18,4)->nullable();
            // $table->integer('m_supplier_id')->comment('{"src":"m_supplier.id"}')->nullable();
            // $table->string('no_draft',20)->nullable();
            // $table->string('no_jurnal',20)->nullable();
            // table->string('no_nota_piutang',20)->nullable();
        });
    }
}
