<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_buku_order_d_aju extends Model
{   
    use ModelTrait;

    protected $table    = 't_buku_order_d_aju';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_buku_order_id","t_ppjk_id","m_customer_id","tanggal","peb_pib","tanggal_peb_pib","no_sppb","tanggal_sppb","creator_id","last_editor_id","delete_id","delete_at"];

    public $columns     = ["id","t_buku_order_id","t_ppjk_id","m_customer_id","tanggal","peb_pib","tanggal_peb_pib","no_sppb","tanggal_sppb","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_buku_order_id:integer","t_ppjk_id:integer","m_customer_id:integer","tanggal:date","peb_pib:integer","tanggal_peb_pib:date","no_sppb:string:20","tanggal_sppb:date","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_buku_order.id=t_buku_order_d_aju.t_buku_order_id","t_ppjk.id=t_buku_order_d_aju.t_ppjk_id","m_customer.id=t_buku_order_d_aju.m_customer_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["t_ppjk_id","m_customer_id","tanggal"];
    public $createable  = ["t_buku_order_id","t_ppjk_id","m_customer_id","tanggal","peb_pib","tanggal_peb_pib","no_sppb","tanggal_sppb","creator_id","last_editor_id","delete_id","delete_at"];
    public $updateable  = ["t_buku_order_id","t_ppjk_id","m_customer_id","tanggal","peb_pib","tanggal_peb_pib","no_sppb","tanggal_sppb","creator_id","last_editor_id","delete_id","delete_at"];
    public $searchable  = ["id","t_buku_order_id","t_ppjk_id","m_customer_id","tanggal","peb_pib","tanggal_peb_pib","no_sppb","tanggal_sppb","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_id', 'id');
    }
    public function t_ppjk() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_ppjk', 't_ppjk_id', 'id');
    }
    public function m_customer() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_customer', 'm_customer_id', 'id');
    }
}
