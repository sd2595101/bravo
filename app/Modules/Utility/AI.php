<?php
namespace App\Modules\Utility;

use QL\QueryList;
use phpQuery;
use App\Modules\Utility\StringUtility;
use Illuminate\Support\Facades\Log;

class AI
{
    const INTERNAL_ENCODING = 'UTF-8';
    
    const SKIP_SITE = [
        'www.liaoshu.net',
        //'www.prwx.com',
        'www.76wx.com',
        'www.81xsw.com',
        'www.sqsxs.com',
    ];
    
    public static function getBlocksites()
    {
        return [
            'http://www.sodu.cc/newmulu_485157_59.html',
            'http://www.sodu.cc/newmulu_485157_64.html',
        ];
    }
    
    public static function isNGSite($url)
    {
        $blocklist = self::getBlocksites();
        if (in_array($url, $blocklist)) {
            return true;
        }
        $host = self::parseUrl($url, PHP_URL_HOST);
        
        return in_array($host, self::SKIP_SITE);
    }
    
    public static function getChapterListNeedle()
    {
        return [
            '返回目录',
            '章节列表',
            '章节目录',
            '查看目录',
            '目录',
            '返回书目',
        ];
    }
    
    /**
     * 
     * @param type $url
     * @param type $return PHP_URL_SCHEME, PHP_URL_HOST, PHP_URL_PORT, PHP_URL_USER, PHP_URL_PASS, PHP_URL_PATH, PHP_URL_QUERY or PHP_URL_FRAGMENT
     */
    public static function parseUrl($url, $return = PHP_URL_HOST)
    {
        if (is_null($return)) {
            return parse_url($url);
        }
        return parse_url($url, $return);
    }
    
    public static function getUrlBase($url)
    {
        $proto = self::parseUrl($url, PHP_URL_SCHEME);
        $host = self::parseUrl($url, PHP_URL_HOST);
        return $proto . '://'.$host.'/';
    }
    
    /**
     * 
     * @param type $html
     * @param type $url
     * @return string
     */
    public static function findChapterListUrl($html, $url)
    {
        Log::info(__CLASS__ . '::' . __METHOD__ . "() -- start --");
        $base = self::getUrlBase($url);
        $links = self::findAllSiteLinks($html);
        $needles = self::getChapterListNeedle();
        
        foreach ($needles as $needle) {
            //$position = array_search($needle, $links);
            $position = false;
            foreach ($links as $val) {
                list ($title, $href) = $val;
                //dump($title . ' -|- ' . $needle . ' -|- ' . $href);
                if ($title == $needle) {
                    Log::info('find [' . $title . '|' . $href . '] from url : ' . $url);
                    $position = $href;
                    break;
                }
            }
            unset($val);
            
            if ($position !== false) {
                $parses = self::parseUrl($position, null);
                if (isset($parses['scheme'])) {
                    return $position;
                }
                return $base . $position;
            }
        }
        return false;
    }
    public static function findContentUrl($chapterListHtml, $chapterTitle, $url)
    {
        $base = self::getUrlBase($url);
        $allLinks = self::findAllSiteLinks($chapterListHtml, $url);
        
        return self::findContentUrlByLinks($chapterTitle, $allLinks);
    }
    
    public static function findContentUrlByLinks($chapterTitle, $allLinks)
    {
        foreach ($allLinks as $val) {
            list ($title, $href) = $val;
            if (self::equalsContentTitle($title, $chapterTitle)) {
                return $href;
            }
        }
        return false;
    }
    
    public static function equalsContentTitle($title1, $title2)
    {
        if ($title1 == $title2) {
            return true;
        }
        $title1 = StringUtility::standardizationChapterTitle($title1);
        $title2 = StringUtility::standardizationChapterTitle($title2);
        if ($title1 == $title2) {
            return true;
        }
        
        $title1 = self::getContentTitleWithoutNumber($title1);
        $title2 = self::getContentTitleWithoutNumber($title2);
        return $title2 == $title1;
    }
    
    public static function getContentTitleWithoutNumber($title)
    {
        $title = StringUtility::standardizationChapterTitle($title);
        $parts = explode(' ', $title);
        
        if (count($parts) == 2) {
            return $parts[1];
        }
        
        return $title;
    }
    
    
    
    
    public static function makeAbsolutePath($href, $pageUrl)
    {
        $base = self::getUrlBase($pageUrl);
        $path = self::parseUrl($pageUrl, PHP_URL_PATH);
        if (substr($path, -1) != '/') {
            $path = dirname($path);
        }
        
        $parses = self::parseUrl($href, null);
        if (isset($parses['scheme'])) {
            return $href;
        } else if (substr($href, 0, 1) == '/') {
            return rtrim($base, '/') . $href;
        }
        
        $raw = rtrim($base, '/') . '/' . trim($path, '/') . '/' . $href;
        
        return str_replace('//', '/', $raw);
    }
    
    public static function findAllSiteLinks($html, $pageUrl = null)
    {
        
        $pqObj = phpQuery::newDocumentHTML($html);
        $charset = self::getCharset($html);
        if ($charset != self::INTERNAL_ENCODING) {
            // if need iconv
        }
        $links = [];
        $doc = phpQuery::newDocumentHTML($html);
        $doc->find('a')->each(function($a) use (&$links){
            $text = StringUtility::trim($a->textContent);
            $href = $a->getAttribute('href');
            if (strpos(strtolower($href), 'javascript') !== false) {
                return;
            }
            
            if ($text == '') {
                return;
            }
            //if (isset($links[$text]) && $links[$text] != $href ) {
            //    //throw new \LogicException('Duplicate link text on ' . $text);
            //}
            //$links[$href] = $text;
            //$links[$text] = $href;
            
            $links[] = [$text, $href];
        });
        if (!is_null($pageUrl)) {
            foreach ($links as $key => $val) {
                
                $links[$key][1] = self::makeAbsolutePath($val[1], $pageUrl);
            }
        }
        return $links;
    }
    
    public static function getCharset($html)
    {
        return strtoupper(phpQuery::newDocumentHTML($html)->charset ?? self::INTERNAL_ENCODING);
    }
}