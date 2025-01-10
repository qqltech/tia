<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class musersd extends Migration
{
    protected $tableName = "m_users_d";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            // $table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->integer('m_responsibility_id')->comment('{"src":"m_responsibility.id"}')->change();
            // $table->integer('default_users_id')->comment('{"fk":"default_users.id"}')->nullable()->change();
        });
    }
}
