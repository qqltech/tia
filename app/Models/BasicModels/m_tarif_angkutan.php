<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_tarif_angkutan extends Model
{   
    use ModelTrait;

    protected $table    = 'm_tarif_angkutan';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["kode","m_supplier_id","tarif","tarif_pengawalan","is_active","sektor","ukuran","jenis","tarif_stapel","jenis_pajak","persen_pajak","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","kena_pajak"];

    public $columns     = ["id","kode","m_supplier_id","tarif","tarif_pengawalan","is_active","sektor","ukuran","jenis","tarif_stapel","jenis_pajak","persen_pajak","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","kena_pajak"];
    public $columnsFull = ["id:bigint","kode:string:20","m_supplier_id:integer","tarif:decimal","tarif_pengawalan:decimal","is_active:boolean","sektor:integer","ukuran:integer","jenis:integer","tarif_stapel:decimal","jenis_pajak:integer","persen_pajak:decimal","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","kena_pajak:boolean"];
    public $rules       = [];
    public $joins       = ["m_supplier.id=m_tarif_angkutan.m_supplier_id","set.m_general.id=m_tarif_angkutan.sektor","set.m_general.id=m_tarif_angkutan.ukuran","set.m_general.id=m_tarif_angkutan.jenis","set.m_general.id=m_tarif_angkutan.jenis_pajak"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["kode","m_supplier_id","tarif","sektor","ukuran","jenis"];
    public $createable  = ["kode","m_supplier_id","tarif","tarif_pengawalan","is_active","sektor","ukuran","jenis","tarif_stapel","jenis_pajak","persen_pajak","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","kena_pajak"];
    public $updateable  = ["kode","m_supplier_id","tarif","tarif_pengawalan","is_active","sektor","ukuran","jenis","tarif_stapel","jenis_pajak","persen_pajak","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","kena_pajak"];
    public $searchable  = ["id","kode","m_supplier_id","tarif","tarif_pengawalan","is_active","sektor","ukuran","jenis","tarif_stapel","jenis_pajak","persen_pajak","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","kena_pajak"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_supplier() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_supplier', 'm_supplier_id', 'id');
    }
    public function sektor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'sektor', 'id');
    }
    public function ukuran() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'ukuran', 'id');
    }
    public function jenis() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'jenis', 'id');
    }
    public function jenis_pajak() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'jenis_pajak', 'id');
    }
}
