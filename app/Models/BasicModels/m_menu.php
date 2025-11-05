<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_menu extends Model
{   
    use ModelTrait;

    protected $table    = 'set.m_menu';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["modul","submodul","menu","path","endpoint","icon","sequence","description","note","truncatable","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];

    public $columns     = ["id","modul","submodul","menu","path","endpoint","icon","sequence","description","note","truncatable","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","modul:string:191","submodul:string:191","menu:string:191","path:string:191","endpoint:string:191","icon:string:191","sequence:decimal","description:string:255","note:string:255","truncatable:boolean","is_active:boolean","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = ["set.m_approval","set.m_role_d"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["modul","menu","path","endpoint","is_active"];
    public $createable  = ["modul","submodul","menu","path","endpoint","icon","sequence","description","note","truncatable","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $updateable  = ["modul","submodul","menu","path","endpoint","icon","sequence","description","note","truncatable","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $searchable  = ["modul","submodul","menu","path","endpoint","icon","sequence","description","note","truncatable","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
