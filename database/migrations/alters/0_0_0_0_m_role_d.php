<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mroled extends Migration
{
    protected $tableName = "set.m_role_d";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->boolean('can_create')->nullable()->default(0)->change();
            // $table->boolean('can_read')->nullable()->default(0)->change();
            // $table->boolean('can_update')->nullable()->default(0)->change();
            // $table->boolean('can_delete')->nullable()->default(0)->change();
            // $table->boolean('can_verify')->nullable()->default(0)->change();
        });
    }
}
