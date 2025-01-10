<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mkontainer extends Migration
{
    protected $tableName = "m_kontainer";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['status']);
            // $table->text('catatan')->nullable()->change();
            // $table->boolean('status')->default(1);
        });
    }
}
