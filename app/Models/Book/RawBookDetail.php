<?php
namespace App\Models\Book;

use Jenssegers\Mongodb\Eloquent\Model;

class RawBookDetail extends Model
{
    //
    protected $connection = 'mongodb';
    protected $collection = 'raw_book_detail';
    
    protected $fillable = [
        'cate',
        'book_name',
        'book_cover',
        'book_url_origin',
        'author',
        'last_chapter_name',
        'last_chapter_url',
        'last_chapter_vip',
        'status',
        'click',
        'last_update',
        'desc',
    ];
}
