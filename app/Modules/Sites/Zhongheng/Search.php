<?php

namespace App\Modules\Sites\Zhongheng;

use App\Modules\Crawler\Search\BuilderInterface as SearchBuilderInterface;

class Search implements SearchBuilderInterface
{
	public function url($keyword)
	{
        $queryUrl  = config('sites.zhongheng.query.url');
        $paramName = config('sites.zhongheng.query.key');
        return $queryUrl . '?' . http_build_query(array($paramName => $keyword));
	}

	public function range()
	{
		return config('sites.zhongheng.query.list.range');
	}

	public function roules()
	{
        return config('sites.zhongheng.query.list.roules');
	}
}