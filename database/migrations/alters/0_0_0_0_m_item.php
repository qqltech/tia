<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mitem extends Migration
{
    protected $tableName = "m_item";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            // $table->boolean('is_bundling')->default(0);
            //$table->dropColumn([ ]);
            // $table->bigInteger('uom_id')->nullable(false)->comment('{"src": "set.m_general.id"}')->change();
            // $table->string('tipe_item', 30)->change();

            $table->boolean('is_bundling')->default(0)->nullable();
        });
    }
}
