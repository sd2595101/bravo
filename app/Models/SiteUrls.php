<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteChapterUrl extends Model
{
    //
    protected $connection = 'mysql';
    protected $fillable = [
        "id",
        "type",
        "url",
        "enable",
    ];
}
