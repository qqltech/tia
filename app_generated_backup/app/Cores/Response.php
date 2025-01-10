<?php
namespace App\Cores;

class Response
{
    public function transform($res){
        if(app()->request->path() == 'me'){
            $res['title'] = 'TIA DEV';
            $res['avatar'] = null;
        }
        return $res;
    }
}