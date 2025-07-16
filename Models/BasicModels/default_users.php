<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class default_users extends Model
{   
    use ModelTrait;

    protected $table    = 'default_users';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["name","email","username","email_verified_at","password","comp_id","is_active","creator_id","last_editor_id","remember_token","created_at","updated_at"];

    public $columns     = ["id","name","email","username","email_verified_at","password","comp_id","is_active","creator_id","last_editor_id","remember_token","created_at","updated_at"];
    public $columnsFull = ["id:bigint","name:string:191","email:string:191","username:string:60","email_verified_at:datetime","password:string:191","comp_id:bigint","is_active:boolean","creator_id:bigint","last_editor_id:bigint","remember_token:string:100","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = [];
    public $details     = ["default_users_socialite"];
    public $heirs       = ["generate_num","generate_num","generate_num_det","generate_num_det","generate_num_log","generate_num_log","generate_num_type","generate_num_type"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [
    "email"=> "unique:default_users,email",
    "username"=> "unique:default_users,username"
	];
    public $required    = ["is_active"];
    public $createable  = ["name","email","username","email_verified_at","password","comp_id","is_active","creator_id","last_editor_id","remember_token","created_at","updated_at"];
    public $updateable  = ["name","email","username","email_verified_at","password","comp_id","is_active","creator_id","last_editor_id","remember_token","created_at","updated_at"];
    public $searchable  = ["name","email","username","email_verified_at","password","comp_id","is_active","creator_id","last_editor_id","remember_token","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function default_users_socialite() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\default_users_socialite', 'default_users_id', 'id');
    }
    
    
}
