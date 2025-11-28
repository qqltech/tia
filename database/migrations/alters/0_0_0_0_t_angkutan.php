<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tangkutan extends Migration
{
    protected $tableName = "t_angkutan";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['code_customer']);
            // $table->boolean('custom_stuple')->nullable()->default(0)->change();
            // $table->boolean('custom_stuple')->nullable()->default(0);
            // $table->string('code_customer')->nullable();
            // $table->boolean('is_special_case')->nullable()->default(0);
        });
    }
}
