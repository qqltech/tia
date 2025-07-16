<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mmenu extends Migration
{
    protected $tableName = "m_menu";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['submodul' ]);
            // $table->dropColumn(['status']);
            // $table->string('type',10)->nullable(false)->change();
            // $table->integer('type')->comment('{"src":"m_general.id"}');
            // $table->integer('submodul')->comment('{"src":"m_general.id"}')->nullable();
            //  $table->string('submodul',20)->nullable()->change();
            // $table->text('catatan')->nullable()->change(); 
            // $table->boolean("status")->default(1);
            // $table->boolean("is_active")->default(1);

        });
    }
}
