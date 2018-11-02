<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use QL\QueryList;
use App\Business\Utility\StringUtility;
use App\Business\Utility\AI;



class TestController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }
	/** 
	* Send a GET requst using cURL 
	* @param string $url to request 
	* @param array $get values to send 
	* @param array $options for cURL 
	* @return string 
	*/ 
	function curl_get($url, array $get = [], array $options = array()) 
	{    
	    $defaults = array( 
	        CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get), 
	        CURLOPT_HEADER => true, 
	        CURLOPT_RETURNTRANSFER => TRUE, 
	        CURLOPT_TIMEOUT => 4 ,
	    ); 
	    
	    $ch = curl_init(); 
	    curl_setopt_array($ch, ($options + $defaults)); 
	    if( ! $result = curl_exec($ch)) 
	    { 
	        trigger_error(curl_error($ch)); 
	    } 
	    curl_close($ch); 
	    return $result; 
	} 
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$url = 'https://www.baidu.com/link?url=ttR4nNelcgsoxGGuictavi5eruNtqEprZZjfFN7KU1-6FBQtNxRAFnIYjr9WVe8Y&wd=&eqid=93d7729b0001c944000000035bbebf7f';
        //dump($url);
        //$result = $this->curl_get($url);
        //dump($result);
        
        $link = '790246.html';
        $page = 'http://www.google.jp/aaa/bbb/';
        $page = 'http://www.google.jp/aaa/ccc';
        $page = 'http://im-bravo.com/book/1201/ctl?flkds=123';
        
        //$page = 'http://www.google.jp';
        
        $res = AI::makeAbsolutePath($link, $page);
        dump($link);
        dump($page);
        dump($res);
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function _index()
    {
        $urls = [
            //'http://192.168.56.101/xiaoshuo/book/672340.html',
            'http://192.168.56.101/test.html',
            //'https://www.aszw.org/book/291/291897/64419967.html',
        ];
        foreach ($urls as $url) {
            $html = QueryList::getInstance()->get($url)->getHtml();
            $res = AI::findChapterListUrl($html, $url);
            dump($res);
        }
        exit;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function aaa()
    {
        
//        $url = 'https://www.google.co.jp/search?q=%E7%83%BD%E7%81%AB%E6%88%8F%E8%AF%B8%E4%BE%AF+%E5%89%91%E6%9D%A5';
//        
//	    $result = QueryList::get($url)->range('body')->rules([
//            'links' => ['h3 a', ['href' => 'href', 'text' => 'text']]
//        ])->query()->getData();
//	    //
//        dump($result);
//        echo 'test';
        //bing
//        $url = 'https://www.bing.com/search?q=%E7%83%BD%E7%81%AB%E6%88%8F%E8%AF%B8%E4%BE%AF+%E5%89%91%E6%9D%A5&mkt=zh-CN';
//	    $result = QueryList::get($url)->range('#b_results')->rules([
//            'links' => ['h2 a', ['href' => 'href', 'text' => 'text']],
//        ])->query()->getData();
//	    //
//        dump($result);

//        $data = QueryList::get('http://192.168.56.101/showchapter/610743.html')
//            ->range('.volume-list>div')
//            ->rules(
//                [
//                    'volume' => [
//                        '.volume',
//                        'text',
//                        '-a -em',
//                        function ($value) {
//                            return StringUtility::trim($value);
//                        }
//                    ],
//                    'chapter-list' => [
//                        '.chapter-list', '*',
//                        '',
//                        function ($list) {
//                            return $list;
//                        }
//                    ],
//                    'chapter-list' => [
//                        '.chapter-list', 'html',
//                        '',
//                        function ($list) {
//                            //dump(pq($list)->elements);
//                            $ql = QueryList::getInstance()->setHtml($list)->find('li');
//                            dump($ql->elements[0]->getAttribute('class'));
//                            exit;
//                            return $list;
//                        }
//                    ]
//                ]
//            )
//            ->query()
//            ->getData(function($data) {
//            return $data;
//        });
//
//        dump($data);
        
        
//        $url = 'https://www.baidu.com/s?wd=' . urlencode('剑来最新章节 烽火戏诸侯');
//	    $result = QueryList::get($url)->range('body')->rules([
//            'list' => ['.result h3 a', ['text' => 'text', 'href' => 'href']],
//        ])->query()->getData(function($elem){
//            //dump($elem);
////            foreach ($elem['list'] as $link) {
////                $href = $link['href'];
////                $res = QueryList::getInstance()->get($href)->html;
////                dump($res);exit;
////            }
//        });
	    //
        //dump($result);
        
        
        
        // 采集该页面[正文内容]中所有的图片
//        $data = QueryList::get('http://cms.querylist.cc/bizhi/453.html')->find('.post_content img')->attrs('src');
//        //打印结果
//        dump($data->all());
//
//
//        // 采集该页面文章列表中所有[文章]的超链接和超链接文本内容
//        $data = QueryList::get('http://cms.querylist.cc/google/list_1.html')->rules([
//            'link' => ['h2>a','href','',function($content){
//                //利用回调函数补全相对链接
//                $baseUrl = 'http://cms.querylist.cc';
//                return $baseUrl.$content;
//            }],
//            'text' => ['h2>a','text']
//        ])->range('.cate_list li')->query()->getData();
//        //打印结果
//        dump($data->all());
    }
}
