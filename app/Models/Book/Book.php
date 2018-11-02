<?php
namespace App\Models\Book;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Book extends Model
{

    //
    protected $connection = 'mongodb';
    
    protected $fillable = [
        "image",
        "image-title",
        "title",
        "book",
        "bookid",
        "category_id",
        "category_name",
        "ulink",
        "uname",
        "clink",
        "cname",
        "length",
        "keyword",
        "keyword-link",
        "vote_info",
        "desc",
    ];

}
