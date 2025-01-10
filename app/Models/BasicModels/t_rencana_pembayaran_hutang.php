<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_rencana_pembayaran_hutang extends Model
{   
    use ModelTrait;

    protected $table    = 't_rencana_pembayaran_hutang';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","no_rph","tgl","total_pi","total_bayar","catatan","creator_id","last_editor_id","delete_id","delete_at","status"];

    public $columns     = ["id","no_draft","no_rph","tgl","total_pi","total_bayar","catatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","status"];
    public $columnsFull = ["id:bigint","no_draft:string:191","no_rph:string:191","tgl:date","total_pi:decimal","total_bayar:decimal","catatan:text","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime","status:string:191"];
    public $rules       = [];
    public $joins       = [];
    public $details     = ["t_rencana_pembayaran_hutang_d"];
    public $heirs       = ["t_pembayaran_hutang"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tgl","status"];
    public $createable  = ["no_draft","no_rph","tgl","total_pi","total_bayar","catatan","creator_id","last_editor_id","delete_id","delete_at","status"];
    public $updateable  = ["no_draft","no_rph","tgl","total_pi","total_bayar","catatan","creator_id","last_editor_id","delete_id","delete_at","status"];
    public $searchable  = ["id","no_draft","no_rph","tgl","total_pi","total_bayar","catatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","status"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_rencana_pembayaran_hutang_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_rencana_pembayaran_hutang_d', 't_rencana_pembayaran_hutang_id', 'id');
    }
    
    
}
