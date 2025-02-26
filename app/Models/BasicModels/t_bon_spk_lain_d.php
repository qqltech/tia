<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_bon_spk_lain_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_bon_spk_lain_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_bon_spk_lain_id","t_spk_lain_d_id","sangu","tambahan","tagihan","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","t_bon_spk_lain_id","t_spk_lain_d_id","sangu","tambahan","tagihan","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_bon_spk_lain_id:integer","t_spk_lain_d_id:integer","sangu:decimal","tambahan:decimal","tagihan:decimal","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_bon_spk_lain.id=t_bon_spk_lain_d.t_bon_spk_lain_id","t_spk_lain_d.id=t_bon_spk_lain_d.t_spk_lain_d_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["t_spk_lain_d_id"];
    public $createable  = ["t_bon_spk_lain_id","t_spk_lain_d_id","sangu","tambahan","tagihan","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["t_bon_spk_lain_id","t_spk_lain_d_id","sangu","tambahan","tagihan","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","t_bon_spk_lain_id","t_spk_lain_d_id","sangu","tambahan","tagihan","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_bon_spk_lain() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_bon_spk_lain', 't_bon_spk_lain_id', 'id');
    }
    public function t_spk_lain_d() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_spk_lain_d', 't_spk_lain_d_id', 'id');
    }
}
