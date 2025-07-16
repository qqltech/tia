<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tbukuorderdaju extends Migration
{
    protected $tableName = "t_buku_order_d_aju";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn([ 'no_ppjk']);
            // $table->integer('peb_pib')->nullable()->change();
            // $table->date('tanggal_peb_pib')->nullable()->change();
            // $table->string('no_sppb',20)->nullable()->change();
            // $table->date('tanggal_sppb')->nullable()->change();
            // table->integer('no_ppjk')->comment('{"src":"t_ppjk.id"}')->nullable();
            $table->string('peb_pib')->change();

        });
    }
}
