<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mkary extends Migration
{
    protected $tableName = "set.m_kary";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            $table->renameColumn('foto_bpjs', 'foto_bpjs_ks')->change();
            // $table->string('foto_bpjs_ktj')->nullable();
            // $table->string('foto_ktp')->nullable();
            // $table->string('foto_kk')->nullable();
            // $table->string('foto_bpjs')->nullable();
            // $table->string('no_rek')->nullable(false)->change();
            // $table->integer('bank_id')->comment('{"src":"set.m_general.id"}')->nullable(false)->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
        });
    }
}
