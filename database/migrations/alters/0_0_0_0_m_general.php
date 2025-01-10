<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mgeneral extends Migration
{
    protected $tableName = "set.m_general";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            // $table->renameColumn('status','is_active');
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->text('deskripsi2')->nullable();
            // $table->text('deskripsi3')->nullable();
            // $table->text('deskripsi4')->nullable();
            
        });
    }
}
