<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = 'sites';
    //
    protected $connection = 'mysql';
    protected $fillable = [
        "id",
        "url",
        "name",
        "official",
        "enable",
        'describe',
        'searcheable',
        'search_url',
        'search_method',
        'search_key',
        'search_example',
        'created_at',
        'updated_at',
    ];
}
