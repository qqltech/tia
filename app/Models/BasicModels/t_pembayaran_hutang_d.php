<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_pembayaran_hutang_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_pembayaran_hutang_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_pembayaran_hutang_id","t_purchase_invoice_id","total_bayar","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","no_pi","tgl_pi","tgl_jt","nilai_hutang","sisa_hutang","bayar","t_jurnal_angkutan_id"];

    public $columns     = ["id","t_pembayaran_hutang_id","t_purchase_invoice_id","total_bayar","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","no_pi","tgl_pi","tgl_jt","nilai_hutang","sisa_hutang","bayar","t_jurnal_angkutan_id"];
    public $columnsFull = ["id:bigint","t_pembayaran_hutang_id:integer","t_purchase_invoice_id:integer","total_bayar:decimal","keterangan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","no_pi:string:191","tgl_pi:date","tgl_jt:date","nilai_hutang:decimal","sisa_hutang:decimal","bayar:decimal","t_jurnal_angkutan_id:integer"];
    public $rules       = [];
    public $joins       = ["t_pembayaran_hutang.id=t_pembayaran_hutang_d.t_pembayaran_hutang_id","t_purchase_invoice.id=t_pembayaran_hutang_d.t_purchase_invoice_id","t_jurnal_angkutan.id=t_pembayaran_hutang_d.t_jurnal_angkutan_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["t_purchase_invoice_id","total_bayar"];
    public $createable  = ["t_pembayaran_hutang_id","t_purchase_invoice_id","total_bayar","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","no_pi","tgl_pi","tgl_jt","nilai_hutang","sisa_hutang","bayar","t_jurnal_angkutan_id"];
    public $updateable  = ["t_pembayaran_hutang_id","t_purchase_invoice_id","total_bayar","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","no_pi","tgl_pi","tgl_jt","nilai_hutang","sisa_hutang","bayar","t_jurnal_angkutan_id"];
    public $searchable  = ["id","t_pembayaran_hutang_id","t_purchase_invoice_id","total_bayar","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","no_pi","tgl_pi","tgl_jt","nilai_hutang","sisa_hutang","bayar","t_jurnal_angkutan_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_pembayaran_hutang() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_pembayaran_hutang', 't_pembayaran_hutang_id', 'id');
    }
    public function t_purchase_invoice() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_purchase_invoice', 't_purchase_invoice_id', 'id');
    }
    public function t_jurnal_angkutan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_jurnal_angkutan', 't_jurnal_angkutan_id', 'id');
    }
}
