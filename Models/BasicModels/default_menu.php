<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class default_menu extends Model
{   
    use ModelTrait;

    protected $table    = 'default_menu';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["project","modul","submodul","menu","path","endpoint","icon","sequence","description","note","truncatable","is_active","created_at","updated_at"];

    public $columns     = ["id","project","modul","submodul","menu","path","endpoint","icon","sequence","description","note","truncatable","is_active","created_at","updated_at"];
    public $columnsFull = ["id:bigint","project:string:191","modul:string:191","submodul:string:191","menu:string:191","path:string:191","endpoint:string:191","icon:string:191","sequence:decimal","description:string:255","note:string:255","truncatable:boolean","is_active:boolean","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["project","modul","menu","path","endpoint","truncatable","is_active"];
    public $createable  = ["project","modul","submodul","menu","path","endpoint","icon","sequence","description","note","truncatable","is_active","created_at","updated_at"];
    public $updateable  = ["project","modul","submodul","menu","path","endpoint","icon","sequence","description","note","truncatable","is_active","created_at","updated_at"];
    public $searchable  = ["project","modul","submodul","menu","path","endpoint","icon","sequence","description","note","truncatable","is_active","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
