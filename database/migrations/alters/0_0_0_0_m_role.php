<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mrole extends Migration
{
    protected $tableName = "m_role";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['status']);
            // $table->string("kode")->nullable(false)->change();
            // $table->boolean('status')->default(1);
        });
    }
}
