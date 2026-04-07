<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_tutup_buku_d_coa extends Model
{   
    use ModelTrait;

    protected $table    = 't_tutup_buku_d_coa';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_tutup_buku_id","m_coa_id","awal","debet","credit","akhir","creator_id","last_editor_id","deletor_id","deleted_at"];

    public $columns     = ["id","t_tutup_buku_id","m_coa_id","awal","debet","credit","akhir","creator_id","last_editor_id","created_at","updated_at","deletor_id","deleted_at"];
    public $columnsFull = ["id:bigint","t_tutup_buku_id:bigint","m_coa_id:bigint","awal:decimal","debet:decimal","credit:decimal","akhir:decimal","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime","deletor_id:bigint","deleted_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_tutup_buku.id=t_tutup_buku_d_coa.t_tutup_buku_id","m_coa.id=t_tutup_buku_d_coa.m_coa_id","default_users.id=t_tutup_buku_d_coa.creator_id","default_users.id=t_tutup_buku_d_coa.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["t_tutup_buku_id","m_coa_id","awal","debet","credit","akhir"];
    public $createable  = ["t_tutup_buku_id","m_coa_id","awal","debet","credit","akhir","creator_id","last_editor_id","deletor_id","deleted_at"];
    public $updateable  = ["t_tutup_buku_id","m_coa_id","awal","debet","credit","akhir","creator_id","last_editor_id","deletor_id","deleted_at"];
    public $searchable  = ["id","t_tutup_buku_id","m_coa_id","awal","debet","credit","akhir","creator_id","last_editor_id","created_at","updated_at","deletor_id","deleted_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_tutup_buku() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_tutup_buku', 't_tutup_buku_id', 'id');
    }
    public function m_coa() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_coa_id', 'id');
    }
    public function creator() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'creator_id', 'id');
    }
    public function last_editor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'last_editor_id', 'id');
    }
}
