<?php

namespace App\Models\CustomModels;

class default_users extends \App\Models\BasicModels\default_users
{    
    public function __construct()
    {
        parent::__construct();
    }

    protected $hidden = ['password'];
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];
  
 public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $check = $model->where("username", req("username"))->exists();
        if ($check && req("username")) {
            return ["errors" => ["Username sudah dipakai"]];
        }

        $check = $model->where("email", req("email"))->exists();
        if ($check && req("email")) {
            return ["errors" => ["Email sudah dipakai"]];
        }

        if (req("password") && req("password") != req("password_confirm")) {
            return ["errors" => ["Konfirmasi password salah"]];
        }

       
        $hasher = app()->make("hash");
        return [
            "model" => $model,
            "data" => array_merge($arrayData, [
                "password" => $hasher->make(req("password")),
            ]),
        ];
    }

    public function updateBefore($model, $arrayData, $metaData, $id = null)
    {
        if (req("password") && !req("password_confirm")) {
            return ["errors" => "Masukkan password Konfirmasi"];
        }

        if (req("password") && req("password") != req("password_confirm")) {
            return ["errors" => "Konfirmasi password salah"];
        }

        $hasher = app()->make("hash");
        return [
            "model" => $model,
            "data" => array_merge($arrayData, [
                "password" => $hasher->make(req("password")),
            ]),
        ];
    }

}