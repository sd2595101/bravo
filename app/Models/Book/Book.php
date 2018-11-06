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
        // ---------------------------------------------------------------
        "book_id",
        "book_info",
        "book_name",
        "author",
        "cate_id",                     // main category id
        "cate_name",                   // main category name
        "categorys",                   //  categorys:  [{'cate_id' => '', 'cate_name' => ''} ...]
        "keywords",                    // add all keyword text in the field.
        "dramatis_personae",           // 
        
    ];

}
