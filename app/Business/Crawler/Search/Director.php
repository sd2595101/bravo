<?php

namespace App\Business\Crawler\Search;
use QL\QueryList;
use Illuminate\Support\Facades\Cache;

class Director
{
	public $builder;

	public function __construct(BuilderInterface $builder)
	{
		$this->builder = $builder;
	}

	public function build($keyword)
	{
	    $key = __CLASS__.'::'.__FUNCTION__ .'::keyword::'.$keyword;
	    
	    if (!Cache::has($key)) {
	        Cache::set($key, $this->rebuild($keyword), 60 * 12);
	    }
	    
	    return Cache::get($key);
	}
    
	private function rebuild($keyword)
	{
		return QueryList::get($this->builder->url($keyword))
			->range($this->builder->range())
			->rules($this->builder->roules())
			->query()
			->getData();
	}
}