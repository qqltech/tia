<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_ba_buku_order extends Model
{   
    use ModelTrait;

    protected $table    = 't_ba_buku_order';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","status","no_ba_buku_order","tanggal","t_buku_order_id","alasan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","filter_tahun"];

    public $columns     = ["id","no_draft","status","no_ba_buku_order","tanggal","t_buku_order_id","alasan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","filter_tahun"];
    public $columnsFull = ["id:bigint","no_draft:string:100","status:string:20","no_ba_buku_order:string:100","tanggal:date","t_buku_order_id:bigint","alasan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","filter_tahun:date"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["t_buku_order_id","alasan"];
    public $createable  = ["no_draft","status","no_ba_buku_order","tanggal","t_buku_order_id","alasan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","filter_tahun"];
    public $updateable  = ["no_draft","status","no_ba_buku_order","tanggal","t_buku_order_id","alasan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","filter_tahun"];
    public $searchable  = ["id","no_draft","status","no_ba_buku_order","tanggal","t_buku_order_id","alasan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","filter_tahun"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
