<?php

namespace App\Modules\Sites\Zhongheng;

use App\Modules\Crawler\Book\BuilderInterface as BookBuilderInterface;

class Book implements BookBuilderInterface
{
    public function url($id)
    {
        return config('sites.zhongheng.book.url')($id);
        //return 'http://192.168.85.101/web/zhongheng-book.html';
    }

    public function range()
    {
        return config('sites.zhongheng.book.range');
    }

    public function roules()
    {
        return config('sites.zhongheng.book.roules');
    }
}