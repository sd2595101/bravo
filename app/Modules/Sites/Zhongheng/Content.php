<?php

namespace App\Modules\Sites\Zhongheng;

use App\Modules\Crawler\Content\BuilderInterface;

class Content implements BuilderInterface
{
    public function url($bookid, $chapterid)
	{
	    return config('sites.zhongheng.content.url')($bookid, $chapterid);
	}

	public function range()
	{
		return false;
	}

	public function roules()
	{
        return config('sites.zhongheng.content.roules');
	}
}