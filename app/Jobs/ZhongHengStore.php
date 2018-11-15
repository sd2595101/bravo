<?php
namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Business\Jobs\ZhongHengStoreAbstract;
use App\Models\Book\RawBookStd;

class ZhongHengStore extends ZhongHengStoreAbstract implements ShouldQueue
{
    protected function crawlerRange()
    {
        return '.main_con li';
    }
    
    protected function crawlerRoules()
    {
        return array(
            'cate' => ['.kind a', 'text', '', function($cate) {
                return \App\Business\Utility\NovelUtility::filterCate($cate);
            }],
            'book_name'         => ['.bookname a', 'text'],
            'book_url_origin'   => ['.bookname a', 'href'],
            'author'            => ['.author a', 'text'],
            'last_chapter_name' => ['.chap a', 'text'],
            'last_chapter_url'  => ['.chap a', 'href'],
            'last_chapter_vip'  => ['.chap em', 'class'],
            'status'            => ['.status', 'text'],
            'click'             => ['.count', 'text'],
            'last_update'       => ['.time', 'text'],
        );
    }
    
    protected function saveData($list)
    {
        foreach ($list as $data) {
            $record = RawBookStd::where('author', '=', $data['author'])->where('book_name','=',$data['book_name'])->get();
            if (!$record) {
                RawBookStd::create($data);
            } else {
                RawBookStd::where('author', '=', $data['author'])->where('book_name','=',$data['book_name'])->update($data, ['upsert' => true]);
            }
        }
    }
 
}
