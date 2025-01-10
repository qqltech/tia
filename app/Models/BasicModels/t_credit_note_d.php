<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_credit_note_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_credit_note_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_credit_note_id","t_purchase_invoice_id","t_tagihan_id","no_urut","sub_total_amount","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","t_credit_note_id","t_purchase_invoice_id","t_tagihan_id","no_urut","sub_total_amount","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_credit_note_id:integer","t_purchase_invoice_id:integer","t_tagihan_id:integer","no_urut:integer","sub_total_amount:decimal","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_credit_note.id=t_credit_note_d.t_credit_note_id","t_purchase_invoice.id=t_credit_note_d.t_purchase_invoice_id","t_tagihan.id=t_credit_note_d.t_tagihan_id"];
    public $details     = ["t_sub_credit_note"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["no_urut"];
    public $createable  = ["t_credit_note_id","t_purchase_invoice_id","t_tagihan_id","no_urut","sub_total_amount","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["t_credit_note_id","t_purchase_invoice_id","t_tagihan_id","no_urut","sub_total_amount","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","t_credit_note_id","t_purchase_invoice_id","t_tagihan_id","no_urut","sub_total_amount","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_sub_credit_note() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_sub_credit_note', 't_credit_note_d_id', 'id');
    }
    
    
    public function t_credit_note() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_credit_note', 't_credit_note_id', 'id');
    }
    public function t_purchase_invoice() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_purchase_invoice', 't_purchase_invoice_id', 'id');
    }
    public function t_tagihan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_tagihan', 't_tagihan_id', 'id');
    }
}
