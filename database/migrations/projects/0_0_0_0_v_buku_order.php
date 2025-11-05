<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class vbukuorder extends Migration
{
    protected $tableName = "v_buku_order";

    
    public function up()
    {
        \DB::unprepared("
            CREATE OR REPLACE VIEW v_buku_order AS 
            SELECT 
                t_buku_order.id,
                t_buku_order.tgl,
                t_buku_order.no_buku_order,
                t_buku_order.no_invoice,
                t_buku_order.no_bl,
                t_buku_order.nama_kapal,
                t_buku_order.jenis_barang,
                t_buku_order.tujuan_asal,
                t_buku_order.nama_pelayaran,
                t_buku_order.kode_pelayaran_id,
                t_buku_order.pelabuhan_id,
                t_buku_order.voyage,
                t_buku_order.status,

                m_customer.kode AS \"m_customer_kode\",
                m_customer.nama_perusahaan AS \"m_customer_nama_perusahaan\",

                (
                    SELECT no_aju 
                    FROM m_generate_no_aju_d aj
                    JOIN t_ppjk ppjk ON aj.id = ppjk.no_ppjk_id
                    WHERE ppjk.t_buku_order_id = t_buku_order.id
                    LIMIT 1
                ) AS \"t_no_aju\",

                (
                    SELECT no_peb_pib 
                    FROM t_ppjk
                    WHERE t_ppjk.t_buku_order_id = t_buku_order.id
                    LIMIT 1
                ) AS \"t_ppjk_no_peb_pib\",

                (
                    SELECT no_nota_rampung 
                    FROM t_nota_rampung
                    WHERE t_nota_rampung.t_buku_order_id = t_buku_order.id
                    LIMIT 1
                ) AS \"no_eir\",

                (
                    SELECT no_prefix 
                    FROM t_buku_order_d_npwp
                    WHERE t_buku_order_d_npwp.t_buku_order_id = t_buku_order.id
                    LIMIT 1
                ) AS \"prefix\",

                (
                    SELECT no_suffix 
                    FROM t_buku_order_d_npwp
                    WHERE t_buku_order_d_npwp.t_buku_order_id = t_buku_order.id
                    LIMIT 1
                ) AS \"sufix\"

            FROM 
                t_buku_order
            LEFT JOIN 
                m_customer ON t_buku_order.m_customer_id = m_customer.id
        ");
    }

    public function down()
    {
        // if( Schema::hasTable($this->tableName) ){
        //     \Cache::put($this->tableName, \DB::table($this->tableName)->get(), 60*30 );
        // }
        Schema::dropIfExists($this->tableName);
    }
}