<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use Jenssegers\Mongodb\Eloquent\Model;

class Sites extends Model
{

    //
    protected $connection = 'mysql';
    protected $fillable = [
        "id",
        "url",
        "enable",
    ];

}
