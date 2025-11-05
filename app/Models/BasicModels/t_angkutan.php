<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_angkutan extends Model
{   
    use ModelTrait;

    protected $table    = 't_angkutan';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","t_buku_order_id","no_angkutan","status","pph","tanggal","party","custom_stuple","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","no_draft","t_buku_order_id","no_angkutan","status","pph","tanggal","party","custom_stuple","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","no_draft:string:20","t_buku_order_id:integer","no_angkutan:string:191","status:string:10","pph:boolean","tanggal:date","party:string:191","custom_stuple:boolean","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_buku_order.id=t_angkutan.t_buku_order_id"];
    public $details     = ["t_angkutan_d"];
    public $heirs       = ["t_jurnal_angkutan_d"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["no_draft","t_buku_order_id","no_angkutan","status","pph","tanggal","party","custom_stuple","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["no_draft","t_buku_order_id","no_angkutan","status","pph","tanggal","party","custom_stuple","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","no_draft","t_buku_order_id","no_angkutan","status","pph","tanggal","party","custom_stuple","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_angkutan_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_angkutan_d', 't_angkutan_id', 'id');
    }
    
    
    public function t_buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_id', 'id');
    }
}
