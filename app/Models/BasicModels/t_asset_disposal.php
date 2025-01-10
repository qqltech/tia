<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_asset_disposal extends Model
{   
    use ModelTrait;

    protected $table    = 't_asset_disposal';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","tipe_disposal","m_customer_id","m_asset_id","tipe_asset","perkiraan_disposal","nilai_jual","tipe_ppn","nominal_ppn","tanggal","no_faktur_pajak","catatan","creator_id","last_editor_id","delete_id","delete_at"];

    public $columns     = ["id","no_draft","tipe_disposal","m_customer_id","m_asset_id","tipe_asset","perkiraan_disposal","nilai_jual","tipe_ppn","nominal_ppn","tanggal","no_faktur_pajak","catatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","no_draft:string:191","tipe_disposal:string:191","m_customer_id:integer","m_asset_id:integer","tipe_asset:string:191","perkiraan_disposal:string:191","nilai_jual:decimal","tipe_ppn:string:191","nominal_ppn:decimal","tanggal:date","no_faktur_pajak:string:191","catatan:text","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_customer.id=t_asset_disposal.m_customer_id","m_asset.id=t_asset_disposal.m_asset_id"];
    public $details     = ["t_asset_disposal_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["no_draft","tipe_disposal","m_customer_id","m_asset_id","tipe_asset","perkiraan_disposal","nilai_jual","tipe_ppn","nominal_ppn","tanggal","no_faktur_pajak"];
    public $createable  = ["no_draft","tipe_disposal","m_customer_id","m_asset_id","tipe_asset","perkiraan_disposal","nilai_jual","tipe_ppn","nominal_ppn","tanggal","no_faktur_pajak","catatan","creator_id","last_editor_id","delete_id","delete_at"];
    public $updateable  = ["no_draft","tipe_disposal","m_customer_id","m_asset_id","tipe_asset","perkiraan_disposal","nilai_jual","tipe_ppn","nominal_ppn","tanggal","no_faktur_pajak","catatan","creator_id","last_editor_id","delete_id","delete_at"];
    public $searchable  = ["id","no_draft","tipe_disposal","m_customer_id","m_asset_id","tipe_asset","perkiraan_disposal","nilai_jual","tipe_ppn","nominal_ppn","tanggal","no_faktur_pajak","catatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_asset_disposal_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_asset_disposal_d', 't_asset_disposal_id', 'id');
    }
    
    
    public function m_customer() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_customer', 'm_customer_id', 'id');
    }
    public function m_asset() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_asset', 'm_asset_id', 'id');
    }
}
