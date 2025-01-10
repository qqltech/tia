<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_pemakaian_stok extends Model
{   
    use ModelTrait;

    protected $table    = 't_pemakaian_stok';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_pemakaian_stok","tanggal","status","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","no_pemakaian_stok","tanggal","status","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","no_pemakaian_stok:string:20","tanggal:date","status:string:191","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = [];
    public $details     = ["t_pemakaian_stok_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["no_pemakaian_stok","tanggal","status","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["no_pemakaian_stok","tanggal","status","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","no_pemakaian_stok","tanggal","status","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_pemakaian_stok_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_pemakaian_stok_d', 't_pemakaian_stok_id', 'id');
    }
    
    
}
