<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tinternald extends Migration
{
    protected $tableName = "t_internal_d";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn('satuan_id');
            //$table->unsignedBigInteger('satuan_id')->comment('{"src": "m_general.id"}')->nullable();
           // $table->boolean('is_bundling')->nullable()->default(false);
        });
    }
}
