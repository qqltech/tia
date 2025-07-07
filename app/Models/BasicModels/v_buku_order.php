<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class v_buku_order extends Model
{   
    use ModelTrait;

    protected $table    = 'v_buku_order';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["tgl","no_buku_order","no_invoice","no_bl","nama_kapal","jenis_barang","tujuan_asal","nama_pelayaran","kode_pelayaran_id","pelabuhan_id","status","m_customer_kode","m_customer_nama_perusahaan","t_no_aju","t_ppjk_no_peb_pib","no_eir","prefix","sufix"];

    public $columns     = ["id","tgl","no_buku_order","no_invoice","no_bl","nama_kapal","jenis_barang","tujuan_asal","nama_pelayaran","kode_pelayaran_id","pelabuhan_id","status","m_customer_kode","m_customer_nama_perusahaan","t_no_aju","t_ppjk_no_peb_pib","no_eir","prefix","sufix"];
    public $columnsFull = ["id:string","tgl:string","no_buku_order:string","no_invoice:string","no_bl:string","nama_kapal:string","jenis_barang:string","tujuan_asal:string","nama_pelayaran:string","kode_pelayaran_id:string","pelabuhan_id:string","status:string","m_customer_kode:string","m_customer_nama_perusahaan:string","t_no_aju:string","t_ppjk_no_peb_pib:string","no_eir:string","prefix:string","sufix:string"];
    public $rules       = "[]";
    public $joins       = [];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = '[]';
    public $createable  = ["tgl","no_buku_order","no_invoice","no_bl","nama_kapal","jenis_barang","tujuan_asal","nama_pelayaran","kode_pelayaran_id","pelabuhan_id","status","m_customer_kode","m_customer_nama_perusahaan","t_no_aju","t_ppjk_no_peb_pib","no_eir","prefix","sufix"];
    public $updateable  = ["tgl","no_buku_order","no_invoice","no_bl","nama_kapal","jenis_barang","tujuan_asal","nama_pelayaran","kode_pelayaran_id","pelabuhan_id","status","m_customer_kode","m_customer_nama_perusahaan","t_no_aju","t_ppjk_no_peb_pib","no_eir","prefix","sufix"];
    public $searchable  = ["tgl","no_buku_order","no_invoice","no_bl","nama_kapal","jenis_barang","tujuan_asal","nama_pelayaran","kode_pelayaran_id","pelabuhan_id","status","m_customer_kode","m_customer_nama_perusahaan","t_no_aju","t_ppjk_no_peb_pib","no_eir","prefix","sufix"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
