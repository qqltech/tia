<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mcustomer extends Migration
{
    protected $tableName = "m_customer";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->BigInteger('jabatan1')->comment('{"src":"set.m_general.id"}')->change();
            // $table->BigInteger('jabatan2')->comment('{"src":"set.m_general.id"}')->change();
            // $table->string('kota', 50);
            // $table->string('kecamatan', 50)->change();
            // $table->float('latitude')->nullable()->change();
            // $table->string('cp2',100)->nullable()->change();
            // $table->float('longtitude')->nullable()->change();
            // $table->boolean("custom_stuple")->default(0)->nullable();
            // $table->integer('jabatan1')->comment('{"src":"set.m_general.id"}')->nullable()->change();
            // $table->integer('jabatan2')->comment('{"src":"set.m_general.id"}')->nullable()->change();
            // $table->integer('coa_piutang')->comment('{"src":"m_coa.id"}')->nullable()->change();
            // $table->integer('m_customer_group_id')->comment('{"src":"m_customer_group.id"}')->nullable()->change();
        });
    }
}
