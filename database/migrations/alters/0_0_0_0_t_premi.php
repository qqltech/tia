<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpremi extends Migration
{
    protected $tableName = "t_premi";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->string("status", 10)->default("DRAFT")->nullable();
            // $table->decimal('total_premi',18,2)->nullable();
            // $table->string('no_draft',40)->nullable();
            // $table->string('no_premi',40)->nullable();
            // $table->date('tgl')->nullable();
            // $table->decimal('tarif_premi',18,4)->nullable();
            // $table->string("status", 20)->default("DRAFT")->nullable()->change();
            $table->decimal('hutang_supir',18,4)->nullable();
            $table->decimal('hutang_dibayar',18,4)->nullable();
            $table->decimal('total_premi_diterima',18,4)->nullable();
        });
    }
}
