<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_tagihan_d_lain extends Model
{   
    use ModelTrait;

    protected $table    = 't_tagihan_d_lain';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_tagihan_id","nominal","keterangan","creator_id","last_editor_id","delete_id","delete_at","tarif_realisasi","qty","is_ppn"];

    public $columns     = ["id","t_tagihan_id","nominal","keterangan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","tarif_realisasi","qty","is_ppn"];
    public $columnsFull = ["id:bigint","t_tagihan_id:integer","nominal:decimal","keterangan:string:191","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime","tarif_realisasi:decimal","qty:integer","is_ppn:boolean"];
    public $rules       = [];
    public $joins       = ["t_tagihan.id=t_tagihan_d_lain.t_tagihan_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nominal","keterangan"];
    public $createable  = ["t_tagihan_id","nominal","keterangan","creator_id","last_editor_id","delete_id","delete_at","tarif_realisasi","qty","is_ppn"];
    public $updateable  = ["t_tagihan_id","nominal","keterangan","creator_id","last_editor_id","delete_id","delete_at","tarif_realisasi","qty","is_ppn"];
    public $searchable  = ["id","t_tagihan_id","nominal","keterangan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","tarif_realisasi","qty","is_ppn"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_tagihan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_tagihan', 't_tagihan_id', 'id');
    }
}
