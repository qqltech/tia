<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tlpbd extends Migration
{
    protected $tableName = "t_lpb_d";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['uom_id']);
            // $table->decimal('harga',18,4)->nullable(false)->change();
            // $table->boolean('is_bundling')->nullable();
        });
    }
}
