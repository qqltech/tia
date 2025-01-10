<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_confirm_asset_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_confirm_asset_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_confirm_asset_id","tgl_penyusutan","nilai_akun_sebelum","nilai_buku_sebelum","nilai_penyusutan","nilai_akun_setelah","nilai_buku_setelah","status","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","t_confirm_asset_id","tgl_penyusutan","nilai_akun_sebelum","nilai_buku_sebelum","nilai_penyusutan","nilai_akun_setelah","nilai_buku_setelah","status","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_confirm_asset_id:integer","tgl_penyusutan:date","nilai_akun_sebelum:decimal","nilai_buku_sebelum:decimal","nilai_penyusutan:decimal","nilai_akun_setelah:decimal","nilai_buku_setelah:decimal","status:string:191","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_confirm_asset.id=t_confirm_asset_d.t_confirm_asset_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tgl_penyusutan","nilai_akun_sebelum","nilai_buku_sebelum","nilai_penyusutan","nilai_akun_setelah","nilai_buku_setelah","status"];
    public $createable  = ["t_confirm_asset_id","tgl_penyusutan","nilai_akun_sebelum","nilai_buku_sebelum","nilai_penyusutan","nilai_akun_setelah","nilai_buku_setelah","status","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["t_confirm_asset_id","tgl_penyusutan","nilai_akun_sebelum","nilai_buku_sebelum","nilai_penyusutan","nilai_akun_setelah","nilai_buku_setelah","status","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","t_confirm_asset_id","tgl_penyusutan","nilai_akun_sebelum","nilai_buku_sebelum","nilai_penyusutan","nilai_akun_setelah","nilai_buku_setelah","status","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_confirm_asset() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_confirm_asset', 't_confirm_asset_id', 'id');
    }
}
