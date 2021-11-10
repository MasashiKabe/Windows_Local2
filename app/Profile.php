<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $guarded = array('id');

    //PHP_Laravel15課題5
    public static $rules = array(
        'name' => 'required',
        'gender' => 'required',
        'hobby' => 'required',
        'introduction' => 'required',
    );

    //PHP_Laravel18追記
    //Profile Modelに関連付けを行う
    public function profilehistories()
    {
        return $this->hasMany('App\Profilehistory');
    }
}
