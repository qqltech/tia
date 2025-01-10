<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_premi_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_premi_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_premi_id","nominal","catatan","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","t_premi_id","nominal","catatan","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_premi_id:integer","nominal:decimal","catatan:text","keterangan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_premi.id=t_premi_d.t_premi_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["t_premi_id","nominal","catatan","keterangan"];
    public $createable  = ["t_premi_id","nominal","catatan","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["t_premi_id","nominal","catatan","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","t_premi_id","nominal","catatan","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_premi() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_premi', 't_premi_id', 'id');
    }
}
