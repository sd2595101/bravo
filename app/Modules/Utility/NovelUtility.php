<?php
namespace App\Modules\Utility;
use App\Modules\Crawler\Book\Director as BookDirector;
use App\Modules\Crawler\Chapter\Director as ChapterDirector;
use App\Modules\Crawler\Content\Director as ContentDirector;


class NovelUtility
{
    
    
    
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
            throw new \App\Modules\Crawler\NoCachedChapterException();
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