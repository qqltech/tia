<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ttagihanlainlain extends Migration
{
    protected $tableName = "t_tagihan_lain_lain";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->dropColumn(['tanggal']);
            // $table->dropColumn(['tanggal_nota']);
            // $table->datetime('tgl');
            // $table->datetime('tgl_nota');
            $table->dropColumn(['m_order_id']);
            $table->integer('no_buku_order')->comment('{"src":"t_buku_order.id"}');
        });
    }
}
