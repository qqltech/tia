<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class tutup_buku_log extends Model
{   
    use ModelTrait;

    protected $table    = 'tutup_buku_log';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_menu_id","menu_path","tahun","jumlah_transaksi","status","keterangan","validation_key","user_id","tanggal_tutup","creator_id","last_editor_id","delete_id","delete_at"];

    public $columns     = ["id","m_menu_id","menu_path","tahun","jumlah_transaksi","status","keterangan","validation_key","user_id","tanggal_tutup","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_menu_id:bigint","menu_path:string:191","tahun:integer","jumlah_transaksi:integer","status:string:191","keterangan:text","validation_key:string:191","user_id:bigint","tanggal_tutup:datetime","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [
    "validation_key"=> "unique:tutup_buku_log,validation_key"
	];
    public $required    = ["m_menu_id","tahun","jumlah_transaksi","status","validation_key","user_id","tanggal_tutup"];
    public $createable  = ["m_menu_id","menu_path","tahun","jumlah_transaksi","status","keterangan","validation_key","user_id","tanggal_tutup","creator_id","last_editor_id","delete_id","delete_at"];
    public $updateable  = ["m_menu_id","menu_path","tahun","jumlah_transaksi","status","keterangan","validation_key","user_id","tanggal_tutup","creator_id","last_editor_id","delete_id","delete_at"];
    public $searchable  = ["id","m_menu_id","menu_path","tahun","jumlah_transaksi","status","keterangan","validation_key","user_id","tanggal_tutup","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
