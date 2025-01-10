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
            // $table->renameColumn('foto_bpjs', 'foto_bpjs_ks')->change();
            // $table->string('foto_bpjs_ktj')->nullable();
            // $table->string('foto_ktp')->nullable();
            // $table->string('foto_kk')->nullable();
            // $table->string('foto_bpjs')->nullable();
            // $table->string('no_rek')->nullable(false)->change();
            // $table->integer('bank_id')->comment('{"src":"set.m_general.id"}')->nullable(false)->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['m_bu_id']);
            // $table->bigInteger('m_bu_id')->comment('{"src":"set.m_business_unit.id"}')->nullable(true);


            // $table->string('nip',20)->nullable(true)->change();	           
            // $table->string('nama', 100)->change();
            // $table->string('no_id', 100)->nullable(true)->change();
            // $table->string('divisi', 20)->change();
            // $table->string('jenis_kelamin', 10)->change();
            // $table->string('alamat_domisili', 100)->change();
            // $table->string('kota_domisili', 20)->change();
            // $table->string('alamat_ktp', 100)->change();
            // $table->string('kota_ktp', 20)->change();
            // $table->string('rt',10)->change();
            // $table->string('rw',10)->change();
            // $table->string('kecamatan', 20)->change();
            // $table->string('status_perkawinan', 30)->change();
            // $table->string('kota_lahir', 20)->change();
            // $table->date('tgl_lahir')->change();
            // $table->date('tgl_mulai')->nullable(true)->change();
            // $table->string('agama', 20)->change();
            // $table->string('email', 100)->nullable(true)->change();
            // $table->string('no_tlp', 20)->nullable(true)->change();
            // $table->integer('bank_id')->comment('{"src":"set.m_general.id"}')->nullable(true)->change();
            // $table->string('no_rek')->nullable(true)->change();
            // $table->string('foto_kary')->nullable(true)->change();
            // $table->string('foto_ktp')->nullable(true)->change();
            // $table->string('foto_kk')->nullable(true)->change();
            // $table->string('foto_bpjs_ks')->nullable(true)->change();
            // $table->string('foto_bpjs_ktj')->nullable(true)->change();
            $table->integer('piutang_id')->comment('{"src":"m_coa.id"}')->nullable();
        });
    }
}
