<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    //PHP_Laravel18追記
    protected $gurrded = array('id');

    public static $rules = array(
        'news_id' => 'required',
        'edided_at' => 'required',
    );
}
