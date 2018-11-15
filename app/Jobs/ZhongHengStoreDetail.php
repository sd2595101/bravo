<?php
namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Business\Jobs\ZhongHengStoreAbstract;
use App\Models\Book\RawBookDetail;

class ZhongHengStoreDetail extends ZhongHengStoreAbstract implements ShouldQueue
{
    protected function crawlerRoules()
    {
        return array(
            'cate' => ['.bookilnk a:ntl-child(1)', 'text', '', function($v) {
                return array_last($v);
            }],
            'book_name'         => ['.bookname a', 'text'],
            'book_cover'        => ['.bookimg img', 'src'],
            'book_url_origin'   => ['.bookname a', 'href'],
            'author'            => ['.bookilnk a:ntl-child(1)', 'text', '', function($v){
                return array_first($v);
            }],
            'last_chapter_name' => ['.bookupdate a', 'text'],
            'last_chapter_url'  => ['.bookupdate a', 'href'],
            'last_chapter_vip'  => ['.chap em', 'class','', function($e){return 'vip';}],
            'status'            => ['.bookilnk span:ntl-child(1)', 'text', '', function($v){
                return array_first($v);
            }],
            'count'             => ['.bookilnk span:ntl-child(2)', 'text', '', function($v){
                return array_last($v);
            }],
            'last_update'       => ['.time', 'text'],
            'desc'              => ['.bookintro', 'text']
        );
    }
    
    protected function crawlerRange()
    {
        return '.store_collist .bookbox';
    }
    
    protected function saveData($list)
    {
        foreach ($list as $data) {
            $record = RawBookDetail::where('author', '=', $data['author'])->where('book_name','=',$data['book_name'])->get();
            if (!$record) {
                RawBookDetail::create($data);
            } else {
                RawBookDetail::where('author', '=', $data['author'])->where('book_name','=',$data['book_name'])->update($data, ['upsert' => true]);
            }
        }
    }
    
}
