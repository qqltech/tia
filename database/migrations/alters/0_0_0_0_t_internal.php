<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tinternal extends Migration
{
    protected $tableName = "t_internal";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn('is_bundling');
            //$table->unsignedBigInteger('satuan_id')->comment('{"src": "m_general.id"}')->nullable();
            
        });
    }
}
