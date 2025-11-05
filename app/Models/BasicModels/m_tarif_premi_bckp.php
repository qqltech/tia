<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_tarif_premi_bckp extends Model
{   
    use ModelTrait;

    protected $table    = 'm_tarif_premi_bckp';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_tarif_premi","sektor_id","ukuran_container","grup_head_id","trip","tagihan","premi","sangu","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","tambahan_tagihan","tambahan_premi","ganti_solar","ganti_solar_premi","tagihan_lain_lain","lain_lain","premig","gansoltag","gansolgab","gansol1","gansol2","gansol3","gansol4","gansol5"];

    public $columns     = ["id","no_tarif_premi","sektor_id","ukuran_container","grup_head_id","trip","tagihan","premi","sangu","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","tambahan_tagihan","tambahan_premi","ganti_solar","ganti_solar_premi","tagihan_lain_lain","lain_lain","premig","gansoltag","gansolgab","gansol1","gansol2","gansol3","gansol4","gansol5"];
    public $columnsFull = ["id:bigint","no_tarif_premi:string:50","sektor_id:integer","ukuran_container:integer","grup_head_id:integer","trip:integer","tagihan:decimal","premi:decimal","sangu:decimal","is_active:boolean","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","tambahan_tagihan:decimal","tambahan_premi:decimal","ganti_solar:decimal","ganti_solar_premi:decimal","tagihan_lain_lain:decimal","lain_lain:decimal","premig:decimal","gansoltag:decimal","gansolgab:decimal","gansol1:decimal","gansol2:decimal","gansol3:decimal","gansol4:decimal","gansol5:decimal"];
    public $rules       = [];
    public $joins       = ["set.m_general.id=m_tarif_premi_bckp.sektor_id","set.m_general.id=m_tarif_premi_bckp.ukuran_container","m_grup_head.id=m_tarif_premi_bckp.grup_head_id","set.m_general.id=m_tarif_premi_bckp.trip"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["no_tarif_premi","sektor_id","ukuran_container","grup_head_id","trip","tagihan","premi","sangu","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","tambahan_tagihan","tambahan_premi","ganti_solar","ganti_solar_premi","tagihan_lain_lain","lain_lain","premig","gansoltag","gansolgab","gansol1","gansol2","gansol3","gansol4","gansol5"];
    public $updateable  = ["no_tarif_premi","sektor_id","ukuran_container","grup_head_id","trip","tagihan","premi","sangu","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","tambahan_tagihan","tambahan_premi","ganti_solar","ganti_solar_premi","tagihan_lain_lain","lain_lain","premig","gansoltag","gansolgab","gansol1","gansol2","gansol3","gansol4","gansol5"];
    public $searchable  = ["id","no_tarif_premi","sektor_id","ukuran_container","grup_head_id","trip","tagihan","premi","sangu","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","tambahan_tagihan","tambahan_premi","ganti_solar","ganti_solar_premi","tagihan_lain_lain","lain_lain","premig","gansoltag","gansolgab","gansol1","gansol2","gansol3","gansol4","gansol5"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function sektor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'sektor_id', 'id');
    }
    public function ukuran_container() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'ukuran_container', 'id');
    }
    public function grup_head() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_grup_head', 'grup_head_id', 'id');
    }
    public function trip() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'trip', 'id');
    }
}
