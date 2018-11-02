<?php
namespace App\Entites\Admin;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Admin extends Model
{

    //
    protected $connection = 'mongodb';
    protected $fillable = [
        "email",
    ];

}
