<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mresponsibility extends Migration
{
    protected $tableName = "set.m_responsibility";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['status']);

            // $table->integer('create_id')->nullable()->change();
            // $table->timestamp('created_at')->nullable()->change();
            // $table->integer('edit_id')->nullable()->change();
            // $table->timestamp('edited_at')->nullable()->change();
            // $table->text("catatan")->nullable()->change();
            // $table->boolean('is_active')->default(1);
        });
    }
}
