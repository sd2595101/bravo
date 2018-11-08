<?php

namespace App\Business\Crawler\Content;
use QL\QueryList;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client as GHttpClient;
use GuzzleHttp\RequestOptions as GROption;
use phpQuery;


class Director
{
    public $builder;
    private static $ghttpclient = null;

    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }
    
    public static function getCacheKey($bookid, $chapterid, $site = null)
    {
        return __CLASS__.'::'. 'build' .'::bookid::'.$bookid . '::chapterid::'.$chapterid . '::site::'.$site;
    }
    
    public static function getCache($bookid, $chapterid, $site = null)
    {
        $key = self::getCacheKey($bookid, $chapterid, $site);
        
        return Cache::get($key);
    }
    
    public static function setCache($bookid, $chapterid, $content)
    {
        $key = self::getCacheKey($bookid, $chapterid, null);
        
        return Cache::forever($key, $content);
    }
    
    public static function clearCache($bookid, $chapterid, $site)
    {
        $key = self::getCacheKey($bookid, $chapterid, $site);
        Cache::forget($key);
    }
    

    public function build($bookid, $chapterid, $site = null)
    {
        $key = __CLASS__.'::'.__FUNCTION__ .'::bookid::'.$bookid . '::chapterid::'.$chapterid . '::site::'.$site;
        
        // debug
        self::clearCache($bookid, $chapterid, $site);
        
        if (!Cache::has($key)) {
            Cache::forever($key, $this->rebuild($bookid, $chapterid, $site));
        }
        
        $result = Cache::get($key);
        
        if (!isset($result['content'])) {
            Cache::forget($key);
        }
        return $result;
    }
    public static function getGHttpClient()
    {
        if (is_null(self::$ghttpclient)) {
            self::$ghttpclient = new GHttpClient();
        }
        return self::$ghttpclient;
    }
    private function rebuild($bookid, $chapterid, $site = null)
    {
        $url = $this->builder->url($bookid, $chapterid);
        //
        //$ql = QueryList::getInstance()->get($url);
        
        if (!$url) {
            
            throw new \Exception('get url error.' . get_class($this->builder));
            
        }
        $host = parse_url($url, PHP_URL_HOST);
        $headers = [
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'Accept-Encoding' => 'gzip, deflate',
            //'Accept-Language' => 'en-US,en;q=0.9,ja;q=0.8',
            //'Cache-Control' => 'max-age=0',
            'Connection' => 'keep-alive',
            //'Host' => $host,
            //'If-None-Match' => '1540971531|',
            //'Upgrade-Insecure-Requests' => '1',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.77 Safari/537.36',
        ];
        
        $client = self::getGHttpClient();
        $realRequestOption = [
            'headers' => $headers,
            GROption::ALLOW_REDIRECTS => [
                'max'             => 10,        // allow at most 10 redirects.
                'protocols'       => ['https','http'],
                'track_redirects' => true
            ],
        ];
        //Cache::forget($url);
        if (!Cache::has($url)) {
            usleep(100);
            $response = $client->request('GET', $url, $realRequestOption);
            $html = $response->getBody()->getContents();
            
            $doc = phpQuery::newDocumentHTML($html);
            $charset = \App\Business\Utility\AI::getCharset($html);
            if ($charset != 'UTF-8') {
                $html = mb_convert_encoding($html, 'UTF-8', $charset);
                $html = str_replace('charset='.$doc->charset,'charset=utf-8',$html);
            }
            
            Cache::set($url, $html, 12 * 60);
        } else {
            //dump($url);
            //Cache::forget($url);
        }
        
        $html = Cache::get($url);
        //dump($html);
        
        $html = \tidy_repair_string($html, [
            'hide-comments' => true,
        ]);
        
        $ql = QueryList::html($html);
        
        $result = $ql
                ->rules($this->builder->roules())
                ->query()
                ->getData();
        
        $info = $result[0] ?? $result;
        $info['original_url'] = $url;
        return $info;
    }
}