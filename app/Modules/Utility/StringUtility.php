<?php
namespace App\Modules\Utility;


class StringUtility
{
    const INNER_ENCODING = 'UTF-8';
    public static function trim($value)
    {
        $value = trim($value, "\\\" \t\r\n");
        $value = strtr($value, array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES)));
        $value = trim(html_entity_decode($value),chr(0xC2).chr(0xA0));
        return $value;
    }
    
    public static function convertEncoding($value, $form = 'GBK')
    {
        return mb_convert_encoding($value, self::INNER_ENCODING, $form);
    }
    public static function makeUnPContent2List($value)
    {
        return $value;
        $text = html_entity_decode($value);
        //
        $text = str_replace(' ', '', $text);
        $text = $value;
        $res = explode("\r\n", $text);
        $res = array_map(function($p){
            return trim($p, ' ');
        }, $res);
        return $res;
    }
    
    public static function divideChapterList($value)
    {
        return $value;
    }
    public static function getZhonghengChapterIdByUrl($href)
    {
        $pregPattern = "/http:\/\/book.zongheng.com\/chapter\/(\d+)\/(\d+)\.html/";
        $matches = null;
        if (preg_match($pregPattern, $href, $matches)) {
            return $matches[2];
        }
        return $href;
    }
    
    public static function getZhonghengBookIdByChapterUrl($href)
    {
        $pregPattern = "/http:\/\/book.zongheng.com\/chapter\/(\d+)\/(\d+)\.html/";
        $matches = null;
        if (preg_match($pregPattern, $href, $matches)) {
            return $matches[1];
        }
        return $href;
    }
    
    public static function compareChapterTitle($title1, $title2)
    {
        
    }
    
    public static function standardizationChapterTitle($title)
    {
        $test = explode(' ', $title, 2);
        if (count($test) != 2) {
            $test = explode('　', $title, 2);
        }
        if (count($test) != 2) {
            $test = explode('：', $title, 2);
        }
        if (count($test) != 2) {
            $test = explode('、', $title, 2);
        }
        $trim = array_map('trim', $test);
        $res = implode(' ', $trim);
        return $res;
    }
}