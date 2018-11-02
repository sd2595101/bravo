<?php
namespace App\Business\Crawler\Chapter;

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
        return __CLASS__ . '::' . __FUNCTION__ . '::bookid::' . $bookid;
    }

    public function build($bookid)
    {
        $key = self::getCacheKey($bookid);
        //Cache::forget($key);
        if (!Cache::has($key)) {
            Cache::set($key, $this->rebuild($bookid), 60 * 12);
        }

        return Cache::get($key);
    }

    private function rebuild($bookid)
    {
        $result = QueryList::get($this->builder->url($bookid))
            ->rules($this->builder->roules())
            ->range($this->builder->range())
            ->query()
            ->getData(function($data) {
            return $data;
        });
        //
        return $result;
    }
    
    public static function getCache($bookid)
    {
        $key = self::getCacheKey($bookid);
        return Cache::get($key);
    }
}
