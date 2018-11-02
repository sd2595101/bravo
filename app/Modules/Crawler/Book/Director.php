<?php

namespace App\Modules\Crawler\Book;
use QL\QueryList;
use Illuminate\Support\Facades\Cache;

class Director
{
	public $builder;

	public function __construct(BuilderInterface $builder)
	{
		$this->builder = $builder;
	}
    public static function getCacheKey($bookid)
    {
        return __CLASS__.'::'. 'build' .'::bookid::'.$bookid;
    }
    
    public static function getCache($bookid)
    {
        $key = self::getCacheKey($bookid);
        
        return Cache::get($key);
    }
	public function build($bookid)
	{
	    $key = __CLASS__.'::'.__FUNCTION__ .'::bookid::'.$bookid;
	    if (!Cache::has($key)) {
	        Cache::forever($key, $this->rebuild($bookid));
	    }
	    
	    return Cache::get($key);
	}
	
	public function rebuild($bookid)
	{
		$result =  QueryList::get($this->builder->url($bookid))
    			->range($this->builder->range())
    			->rules($this->builder->roules())
    			->query()
    			->getData();
        $info = $result[0] ?? false;
        return $info;
	}
}