<?php
namespace App\Models\Book;

use Jenssegers\Mongodb\Eloquent\Model;

class RawBookStd extends Model
{
    //
    protected $connection = 'mongodb';
    protected $collection = 'raw_book_std';
    
    protected $fillable = [
        'cate',
        'book_name',
        'book_url_origin',
        'author',
        'last_chapter_name',
        'last_chapter_url',
        'last_chapter_vip',
        'status',
        'daily_click',
        'last_update',
    ];
}
