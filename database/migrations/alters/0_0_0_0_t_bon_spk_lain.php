<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tbonspklain extends Migration
{
    protected $tableName = "t_bon_spk_lain";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->integer('operator')->comment('{"src":"set.m_kary.id"}')->nullable()->change();
        });
    }
}
