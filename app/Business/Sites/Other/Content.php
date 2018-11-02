<?php
namespace App\Business\Sites\Other;

use Illuminate\Support\Facades\Cache;
use App\Business\Crawler\Content\BuilderInterface;
use QL\QueryList;
use App\Business\Crawler\Book\Director as BookDirector;
use App\Business\Crawler\Content\Director as ContentDirector;
use App\Business\Utility\StringUtility;
use App\Business\Utility\AI;
use Illuminate\Support\Facades\Log;

class Content implements BuilderInterface
{
    const SLEEP_M = 500;

    public function url($bookid, $chapterid)
    {
        //return 'http://192.168.56.101/view-source_https___www.prwx.com_book_1265_609664.html';
        
        Log::info(__CLASS__ . '::' . __METHOD__ . "() == START ==");
        $key = self::rawCacheKey($bookid);
        $chapter = ContentDirector::getCache($bookid, $chapterid);
        $chapterTitle = $chapter['title'] = StringUtility::standardizationChapterTitle($chapter['title']);
        Log::info(__CLASS__ . '::' . __METHOD__ . "() find [{$bookid}][{$chapterid}][$chapterTitle] chapter content url start");
        
        //exit;
        if (Cache::has($key)) {
            Log::info(__CLASS__ . '::' . __METHOD__ . "() Has raw_chapter_list Cached . |=>");
            $rawlist = Cache::get($key);
            
            $url = AI::findContentUrlByLinks($chapter['title'], $rawlist);
            Log::info(__CLASS__ . '::' . __METHOD__ . "() result url : {$url}");
            //dump($rawlist);exit;
            if ($url) {
                return $url;
            } else {
                $clurl = Cache::get(self::chapterlistCacheKey($bookid));
                
                if ($clurl){
                    $url = self::findChapterByAnyChapterUrl($bookid, $chapter['title'], $clurl);
                } else {
                    Cache::forget($key);
                    return $this->getContentUrl($bookid, $chapterid);
                }
            }
            return $url;
        }
        Log::info(__CLASS__ . '::' . __METHOD__ . "() Not raw_chapter_list Cached . |=>");
        
        
        return $this->getContentUrl($bookid, $chapterid);
    }
    private static function setCacheRawChapterList($bookid, $list, $capterlisturl)
    {
        Log::info(__CLASS__ . '::' . __METHOD__ . "() -- start --");
        $key = self::rawCacheKey($bookid);
        Cache::forever($key, $list);
        $urlKey = self::chapterlistCacheKey($bookid);
        Cache::forever($urlKey, $capterlisturl);
    }
    
    public static function rawCacheKey($bookid)
    {
        return 'raw_chapter_list_' . $bookid;
    }
    public static function chapterlistCacheKey($bookid)
    {
        return 'raw_chapter_list_link_' . $bookid;
    }
    
    
    private function getContentUrl($bookid, $chapterid)
    {
        Log::info(__CLASS__ . '::' . __METHOD__ . ' == START ==' );
        $book = BookDirector::getCache($bookid);
        $chapter = ContentDirector::getCache($bookid, $chapterid);
        $title = trim($book[ 'title' ]);
        $chapter['title'] = StringUtility::standardizationChapterTitle($chapter['title']);
        // search by book name for sodu.cc
        $queryurl = 'http://www.sodu.cc/result.html?searchstr=' . \urlencode($title);
        Log::info('sodu query url:' . $queryurl);
        $q = Cache::get($queryurl, function() use ($queryurl, $title) {
                usleep(self::SLEEP_M);
                $q = QueryList::getInstance()
                    ->get($queryurl)
                    ->range('body')
                    ->rules([
                        'links' => ['.main-html a', ['text' => 'text', 'href' => 'href'], ''],
                    ])
                    ->query()
                    ->getData(function($e) use ($title) {
                        foreach ($e[ 'links' ] as $link) {
                            if (trim($link[ 'text' ]) == $title) {
                                return $link[ 'href' ];
                            }
                        }
                        return false;
                    }
                );
                Log::info('cached ' . $queryurl);
                Cache::forever($queryurl, $q);
                return $q;
            }
        );
        // get the book url
        $url = $q[ 0 ] ?? null;
        Log::info(__CLASS__ . '::' . __METHOD__ . ' SODU search result :  ' . $url );
        
        if (!$url) {
            //dump($queryurl);
            //dump($q);
            Log::warn('search book url from sodu.');
            throw new \Exception('error : search book url from sodu.');
        }
        // find the book chapter-list url
        $q2 = Cache::get($url, function() use ($url){
            if (Cache::has($url)) {
                return Cache::get($url);
            }
            usleep(self::SLEEP_M);
            $q2 = QueryList::getInstance()
                ->get($url)
                ->range('body')
                ->rules([
                    'links' => ['.main-html a.tl', ['text' => 'text', 'href' => 'href'], ''],
                ])
                ->query()
                ->getData();
            Log::info('cached ' . $url);
            Cache::forever($url, $q2);
            return $q2;
        });
        
        $contentUrl = false;
        $menuurl = null;
        
        for ($site = 0; $site < 10; $site ++) {
            
            $menuurl = $q2[ 0 ][ 'links' ][ $site ][ 'href' ] ?? false;
            
            if (AI::isNGSite($menuurl)) {
                Log::info('block url : ' . $menuurl);
                continue;
            }
            
            Log::info('find for sodu menu url : ' . $menuurl);
            
            //Cache::forget($menuurl);
            try {
                $q3 = Cache::get($menuurl, function() use ($menuurl) {
                    usleep(self::SLEEP_M);
                    $q3 = QueryList::getInstance()
                    ->get($menuurl)
                    ->rules([
                        'chapterlist' => ['td>a', ['href' => 'href','text' => 'text']],
                    ])
                    ->range('table')
                    ->query()
                    ->getData(function($e) {
                        $e['chapterlist'] = array_map(function($one){
                            $parts = explode('chapterurl=', $one['href'] ?? '');
                            $one['href'] = array_pop($parts);
                            return $one;
                        }, $e['chapterlist'] ?? []);
                        return $e;
                    });
                    Log::info('cached url : ' . $menuurl);
                    Log::info('cached content : ');
                    //Log::info($q3->all());
                    Cache::forever($menuurl, $q3);
                    return $q3;
                });
            } catch (\Exception $ex) {
                echo $ex;
                dump($menuurl);
                continue;
            }
            
            $chapterlist = $q3[3]['chapterlist'];
            // check site
            //dump($q3);exit;
            
            $siteCheckFirstChapterLink = $chapterlist[0]['href'] ?? '';
            
            // NG Site validation
            if ( AI::isNGSite($siteCheckFirstChapterLink )) {
                
                continue;
                
            }
            // find content link for SODU chapter list.
            foreach ($chapterlist as $chapterlink) {
                if (AI::equalsContentTitle($chapterlink['text'],$chapter['title'])) {
                    Log::info('fixed url ' . $chapterlink['href']);
                    $contentUrl = $chapterlink['href'];
                    break;
                }
            }
            if ($contentUrl !== false) {
                Log::info('break.');
                break;
            }
            
            // if un maching the chapter name
            // go to the first chapter page and find chapter list
            $anyContentUrl = $q3[3]['chapterlist'][0]['href'] ?? '';
            Log::info('any content url:' . $anyContentUrl);
            if (!$anyContentUrl) {
                Log::info('any content url is empty : BREAK.');
                break;
            }
            Log::info('start find chapter-list for a site content url:' . $anyContentUrl);
            //dump($anyContentUrl);
            try {
                $anyContentHtml = QueryList::getInstance()->get($anyContentUrl)->getHtml();
                usleep(self::SLEEP_M);
                //Log::debug($anyContentHtml);
                $clurl = AI::findChapterListUrl($anyContentHtml, $anyContentUrl);
                
                if (!$clurl) {
                    Log::info('find site link empty. go to next. ' . $clurl);
                    continue;
                }
                
                Log::info('find site chapter url : ' . $clurl);
                
                $contentUrl = self::findChapterByAnyChapterUrl($bookid, $chapter['title'], $clurl);
                
                if ($contentUrl) {
                    Log::info('[1]content url : ' .$contentUrl);
                    return $contentUrl;
                } else {
                    Log::info('[2] not maching content url from  ' .$anyContentUrl);
                }
                
            } catch (\Exception $ex) {
                //dump($ex);
                Log::info('find site content url error:');
                Log::error($ex);
                continue;
            }
        }
        if (!$contentUrl) {
            Log::info('find ALL site content url error:');
            throw new \Exception('error : search content url from sodu.');
        }
        return $contentUrl;
    }
    
    
    
    public static function findChapterByAnyChapterUrl($bookid, $findTitle, $clurl)
    {
        Log::info(__CLASS__ . '::' . __METHOD__ . "() -- start --" . $clurl);
        
        
        $clHtml = QueryList::getInstance()->get($clurl)->getHtml();
        $contentUrl = AI::findContentUrl($clHtml, $findTitle, $clurl);
        //Log::debug($clHtml);
        Log::info('find site content url : ' . $contentUrl);
        if ($contentUrl) {
            Log::info('[9]content url : ' .$contentUrl);
            $rawCacheChapterList = AI::findAllSiteLinks($clHtml, $clurl);
            self::setCacheRawChapterList($bookid, $rawCacheChapterList, $clurl);
            return $contentUrl;
        }
        
        return false;
    }
    
    
    
    public function range()
    {
        return false;
    }

    public function roules()
    {
        return config('sites.76wx.content.roules');
    }
}
