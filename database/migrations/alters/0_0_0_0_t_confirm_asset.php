<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tconfirmasset extends Migration
{
    protected $tableName = "t_confirm_asset";

    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            // $table->dropColumn([
            //     "tgl_asset",
            //     "m_item_id",
            //     "m_perkiraan_akun_penyusutan",
            //     "kategori_id",
            //     "m_perkiraan_asset_id",
            //     "m_perkiraan_by_akun_penyusutan",
            //     "filter_tahun",
            //     "edited_at",
            //     "deletor_id",
            // ]);

            // $table
            //     ->string("no_draft", 20)
            //     ->nullable()
            //     ->after("id");
            // $table
            //     ->string("kode_asset", 100)
            //     ->nullable()
            //     ->after("no_draft");
            // $table
            //     ->string("no_asset_confirmation", 250)
            //     ->nullable()
            //     ->after("kode_asset");
            // $table
            //     ->string("tipe_asset", 50)
            //     ->nullable()
            //     ->after("no_asset_confirmation");

            // $table
            //     ->bigInteger("t_lpb_d_id")
            //     ->nullable()
            //     ->comment('{"src":"t_lpb_d.id"}');

            // $table
            //     ->bigInteger("status_id")
            //     ->nullable()
            //     ->comment('{"src":"set.m_general.id"}');

            // $table->date("tanggal")->nullable();
            // $table->date("tanggal_pakai")->nullable();

            // $table->string("nama_asset", 500)->nullable();

            // $table->decimal("nilai_minimal", 15, 2);

            // $table
            //     ->bigInteger("coa_penyusutan")
            //     ->nullable()
            //     ->comment('{"src":"m_coa.id"}');

            // $table
            //     ->bigInteger("coa_by_akun_penyusutan")
            //     ->nullable()
            //     ->comment('{"src":"m_coa.id"}');

            // $table
            //     ->bigInteger("coa_asset")
            //     ->nullable()
            //     ->comment('{"src":"m_coa.id"}');

            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn([ 'status' ]);
            // $table->date('filter_tahun')->nullable();
            // $table->renameColumn('status_id','status');
            // $table->string('status',20)->nullable();
        });
    }
}
