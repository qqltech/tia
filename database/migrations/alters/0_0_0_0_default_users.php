<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class defaultusers extends Migration
{
    protected $tableName = "default_users";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            // $table->string('_columnName_');
            // $table->dropColumn(['catatan']);
            // $table->text('catatan')->nullable();
            // $table->string('name')->nullable();
            // $table->string('user_login',45)->default('trial');
            // $table->integer('m_employee_id')->comment('{"src":"m_kary.id"}')->nullable();
            // $table->string("tipe",15)->default("ADMIN");
            // $table->integer("create_id")->nullable();
            // $table->integer("edit_id")->nullable();
            // $table->timestamp("edited_at")->nullable();
            // $table->integer("delete_id")->nullable();
            // $table->timestamp("deleted_at")->nullable();

        });
    }
}
