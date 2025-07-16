<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mapproval extends Migration
{
    protected $tableName = "set.m_approval";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            // $table->renameColumn('status','is_active');
            // $table->bigInteger('menu')->comment('{"src":"set.m_menu.id"}')->change();
            //$table->dropColumn([ ]);
        });
    }
}
