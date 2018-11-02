<?php
namespace App\Business\Utility;

use Illuminate\Support\Facades\Cookie;
use App\Business\Crawler\Book\Director as BookDirector;
use App\Business\Crawler\Chapter\Director as ChapterDirector;
use App\Business\Crawler\Content\Director as ContentDirector;

use App\Business\Sites\Zhongheng\Chapter;
use App\Business\Sites\Zhongheng\Book;
use App\Business\Sites\Zhongheng\Content as ZhonghengContent;
use App\Business\Sites\Other\Content as OtherContent;

class NovelUtility
{
    const HISTORY_COOKIE_NAME = 'BRAVO-BROWSING-HISTORY';
    
    
    public static function setBrowsingHistory($bookid, $chapterid)
    {
        $history = unserialize(Cookie::get(self::HISTORY_COOKIE_NAME)) ?? [];
        
        if (!is_array($history)) {
            $history = [];
        }
        
        if (!array_key_exists($bookid, $history)) {
            $history[$bookid] = $chapterid;
        }
        Cookie::unqueue(self::HISTORY_COOKIE_NAME);
        Cookie::queue(self::HISTORY_COOKIE_NAME, serialize($history), 60 * 24 * 365);
    }
    
    public static function getBrowsingHistoryList()
    {
        return unserialize(Cookie::get(self::HISTORY_COOKIE_NAME)) ?? [];
    }
    
    public static function convertZHChaptersVolumeMerge($list)
    {
        $res = [];
        
        foreach ($list as $vg => $v) {
            $clist = $v['chapter-list'] ?? [];
            foreach ($clist as $c) {
                $c['vg'] = $vg;
                $c['volume'] = $v['volume'];
                $res[] = $c;
            }
        }
        
        $chapters = [];
        
        foreach ($res as $key => $val) {
            
            $prevKey = $key - 1;
            $nextKey = $key + 1;
            $val['prev'] = $res[$prevKey]['chapterid'] ?? '';
            $val['next'] = $res[$nextKey]['chapterid'] ?? '';
            $chapters[$val['chapterid']] = $val;
        }
        
        return $chapters;
    }
    
    public static function getZHBookInfo2ContentData($bookid, $chapterid)
    {
        $bookinfo = BookDirector::getCache($bookid);
        $bkinfo = self::getZHBookInfo($bookid, $chapterid);
        $contentInfo = [
            'content' => [],
            'volume' => '',
            'title' => '',
            'uname' => $bookinfo['uname'] ?? '',
            'isvip' => true,
            'original_url' => '',
        ];
        
        return array_merge($contentInfo, $bkinfo);
    }
    
    
    
    public static function getZHBookInfo($bookid, $chapterid)
    {
        $result = [
            'isvip' => true,
            'volume' => '',
            'title' => '',
            'original_url' => '',
            'zhvip' => false,
        ];
        $chapters = ChapterDirector::getCache($bookid);
        if (is_null($chapters)) {
            throw new \App\Business\Crawler\NoCachedChapterException();
        }
        foreach($chapters as $vl) {
            foreach ($vl['chapter-list'] as $c) {
                if ($c['chapterid'] == $chapterid) {
                    $result['volume'] = $vl['volume'];
                    $result['title'] = $c['chaptername'];
                    $result['original_url'] = $c['href'];
                }
            }
            if (in_array($chapterid, $vl['chapter-list-vip'] ?? [])) {
                $result['isvip'] = true;
                $result['zhvip'] = true;
            }
        }
        return $result;
    }
}