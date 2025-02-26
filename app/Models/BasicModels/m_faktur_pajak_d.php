<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_faktur_pajak_d extends Model
{   
    use ModelTrait;

    protected $table    = 'm_faktur_pajak_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_faktur_pajak_id","no_faktur_pajak","referensi","no_nota","is_active","creator_id","last_editor_id","delete_id","delete_at"];

    public $columns     = ["id","m_faktur_pajak_id","no_faktur_pajak","referensi","no_nota","is_active","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_faktur_pajak_id:integer","no_faktur_pajak:string:191","referensi:string:191","no_nota:string:191","is_active:boolean","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_faktur_pajak.id=m_faktur_pajak_d.m_faktur_pajak_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["no_faktur_pajak"];
    public $createable  = ["m_faktur_pajak_id","no_faktur_pajak","referensi","no_nota","is_active","creator_id","last_editor_id","delete_id","delete_at"];
    public $updateable  = ["m_faktur_pajak_id","no_faktur_pajak","referensi","no_nota","is_active","creator_id","last_editor_id","delete_id","delete_at"];
    public $searchable  = ["id","m_faktur_pajak_id","no_faktur_pajak","referensi","no_nota","is_active","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_faktur_pajak() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_faktur_pajak', 'm_faktur_pajak_id', 'id');
    }
}
