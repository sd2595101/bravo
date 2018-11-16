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
        "book_name",
        "author",
        "desc",
        "cate_name",                   // main category name
        "cate_store_url",              // 
        "categorys",                   //  categorys:  [{'cate_id' => '', 'cate_name' => ''} ...]
        "keywords",                    // add all keyword text in the field.
        "dramatis_personae",           // zhu ren gong
        "keyword_summary",
        "keyword_1",
        "keyword_2",
        "keyword_3",
        "keyword_4",
        "keyword_5",
        "vote",
    ];

}
