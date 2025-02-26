<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_faktur_pajak extends Model
{   
    use ModelTrait;

    protected $table    = 'm_faktur_pajak';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["prefix","no_awal","no_akhir","tgl_pembuatan","start_date","end_date","creator_id","last_editor_id","edited_at","delete_id","deleted_at"];

    public $columns     = ["id","prefix","no_awal","no_akhir","tgl_pembuatan","start_date","end_date","creator_id","last_editor_id","edited_at","delete_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","prefix:string:191","no_awal:integer","no_akhir:integer","tgl_pembuatan:date","start_date:date","end_date:date","creator_id:integer","last_editor_id:integer","edited_at:datetime","delete_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = [];
    public $details     = ["m_faktur_pajak_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["prefix","no_awal","no_akhir","tgl_pembuatan","start_date","end_date"];
    public $createable  = ["prefix","no_awal","no_akhir","tgl_pembuatan","start_date","end_date","creator_id","last_editor_id","edited_at","delete_id","deleted_at"];
    public $updateable  = ["prefix","no_awal","no_akhir","tgl_pembuatan","start_date","end_date","creator_id","last_editor_id","edited_at","delete_id","deleted_at"];
    public $searchable  = ["id","prefix","no_awal","no_akhir","tgl_pembuatan","start_date","end_date","creator_id","last_editor_id","edited_at","delete_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function m_faktur_pajak_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_faktur_pajak_d', 'm_faktur_pajak_id', 'id');
    }
    
    
}
