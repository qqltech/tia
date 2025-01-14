<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_buku_order extends Model
{   
    use ModelTrait;

    protected $table    = 't_buku_order';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["tgl","tipe_order","no_buku_order","m_customer_id","jenis_barang","sektor","tujuan_asal","moda_transportasi","coo","hc","status","tanggal_closing_doc","jam_closing_doc","tanggal_closing_cont","jam_closing_cont","no_bl","tanggal_bl","no_invoice","tanggal_invoice","tanggal_pengkont","tanggal_pemasukan","jumlah_coo","lembar_coo","jumlah_coo_ulang","lembar_coo_ulang","nama_pelayaran","no_boking","voyage","gw","nw","catatan","nama_kapal","dispensasi_closing_cont","dispensasi_closing_doc","angkutan","jumlah_kemasan","creator_id","last_editor_id","delete_id","delete_at","pelabuhan_id","lokasi_stuffing","kode_pelayaran_id","tipe","tgl_etd_eta","genzet"];

    public $columns     = ["id","tgl","tipe_order","no_buku_order","m_customer_id","jenis_barang","sektor","tujuan_asal","moda_transportasi","coo","hc","status","tanggal_closing_doc","jam_closing_doc","tanggal_closing_cont","jam_closing_cont","no_bl","tanggal_bl","no_invoice","tanggal_invoice","tanggal_pengkont","tanggal_pemasukan","jumlah_coo","lembar_coo","jumlah_coo_ulang","lembar_coo_ulang","nama_pelayaran","no_boking","voyage","gw","nw","catatan","nama_kapal","dispensasi_closing_cont","dispensasi_closing_doc","angkutan","jumlah_kemasan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","pelabuhan_id","lokasi_stuffing","kode_pelayaran_id","tipe","tgl_etd_eta","genzet"];
    public $columnsFull = ["id:bigint","tgl:date","tipe_order:string:10","no_buku_order:string:20","m_customer_id:integer","jenis_barang:string:250","sektor:integer","tujuan_asal:string:100","moda_transportasi:string:50","coo:string:50","hc:string:50","status:string:10","tanggal_closing_doc:date","jam_closing_doc:time","tanggal_closing_cont:date","jam_closing_cont:time","no_bl:string:50","tanggal_bl:date","no_invoice:string:50","tanggal_invoice:date","tanggal_pengkont:date","tanggal_pemasukan:date","jumlah_coo:integer","lembar_coo:integer","jumlah_coo_ulang:integer","lembar_coo_ulang:integer","nama_pelayaran:string:250","no_boking:string:20","voyage:string:20","gw:string:20","nw:string:20","catatan:text","nama_kapal:string:100","dispensasi_closing_cont:boolean","dispensasi_closing_doc:boolean","angkutan:string:100","jumlah_kemasan:integer","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime","pelabuhan_id:integer","lokasi_stuffing:string:191","kode_pelayaran_id:integer","tipe:integer","tgl_etd_eta:date","genzet:string:100"];
    public $rules       = [];
    public $joins       = ["m_customer.id=t_buku_order.m_customer_id","set.m_general.id=t_buku_order.sektor","set.m_general.id=t_buku_order.pelabuhan_id","set.m_general.id=t_buku_order.kode_pelayaran_id","set.m_general.id=t_buku_order.tipe"];
    public $details     = ["t_buku_order_d_aju","t_buku_order_d_npwp","t_buku_order_detber"];
    public $heirs       = ["t_angkutan","t_bon_dinas_luar_d","t_ppjk","t_bkk","t_komisi","t_komisi","t_bkm_non_order","t_surat_jalan","t_komisi_d","t_tagihan","t_spk_lain","t_bkm","t_tagihan_d_npwp","t_spk_angkutan","t_spk_angkutan","t_bll","t_nota_rampung","t_komisi_undername","t_buku_penyesuaian","t_dp_penjualan"];
    public $detailsChild= [];
    public $detailsHeirs= ["t_surat_jalan","t_spk_angkutan","t_spk_angkutan","t_nota_rampung_d"];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["tgl","tipe_order","no_buku_order","m_customer_id","jenis_barang","sektor","tujuan_asal","moda_transportasi","coo","hc","status","tanggal_closing_doc","jam_closing_doc","tanggal_closing_cont","jam_closing_cont","no_bl","tanggal_bl","no_invoice","tanggal_invoice","tanggal_pengkont","tanggal_pemasukan","jumlah_coo","lembar_coo","jumlah_coo_ulang","lembar_coo_ulang","nama_pelayaran","no_boking","voyage","gw","nw","catatan","nama_kapal","dispensasi_closing_cont","dispensasi_closing_doc","angkutan","jumlah_kemasan","creator_id","last_editor_id","delete_id","delete_at","pelabuhan_id","lokasi_stuffing","kode_pelayaran_id","tipe","tgl_etd_eta","genzet"];
    public $updateable  = ["tgl","tipe_order","no_buku_order","m_customer_id","jenis_barang","sektor","tujuan_asal","moda_transportasi","coo","hc","status","tanggal_closing_doc","jam_closing_doc","tanggal_closing_cont","jam_closing_cont","no_bl","tanggal_bl","no_invoice","tanggal_invoice","tanggal_pengkont","tanggal_pemasukan","jumlah_coo","lembar_coo","jumlah_coo_ulang","lembar_coo_ulang","nama_pelayaran","no_boking","voyage","gw","nw","catatan","nama_kapal","dispensasi_closing_cont","dispensasi_closing_doc","angkutan","jumlah_kemasan","creator_id","last_editor_id","delete_id","delete_at","pelabuhan_id","lokasi_stuffing","kode_pelayaran_id","tipe","tgl_etd_eta","genzet"];
    public $searchable  = ["id","tgl","tipe_order","no_buku_order","m_customer_id","jenis_barang","sektor","tujuan_asal","moda_transportasi","coo","hc","status","tanggal_closing_doc","jam_closing_doc","tanggal_closing_cont","jam_closing_cont","no_bl","tanggal_bl","no_invoice","tanggal_invoice","tanggal_pengkont","tanggal_pemasukan","jumlah_coo","lembar_coo","jumlah_coo_ulang","lembar_coo_ulang","nama_pelayaran","no_boking","voyage","gw","nw","catatan","nama_kapal","dispensasi_closing_cont","dispensasi_closing_doc","angkutan","jumlah_kemasan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","pelabuhan_id","lokasi_stuffing","kode_pelayaran_id","tipe","tgl_etd_eta","genzet"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_buku_order_d_aju() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_buku_order_d_aju', 't_buku_order_id', 'id');
    }
    public function t_buku_order_d_npwp() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_buku_order_d_npwp', 't_buku_order_id', 'id');
    }
    public function t_buku_order_detber() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_buku_order_detber', 't_buku_order_id', 'id');
    }
    
    
    public function m_customer() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_customer', 'm_customer_id', 'id');
    }
    public function sektor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'sektor', 'id');
    }
    public function pelabuhan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'pelabuhan_id', 'id');
    }
    public function kode_pelayaran() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'kode_pelayaran_id', 'id');
    }
    public function tipe() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe', 'id');
    }
}
