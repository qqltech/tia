<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mjasa extends Migration
{
    protected $tableName = "m_jasa";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['status']);
            // $table->text('catatan')->nullable()->change();
            // $table->boolean('is_active')->default(1);
        });
    }
}
