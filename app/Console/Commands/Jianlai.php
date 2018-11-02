<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use QL\QueryList;

class Jianlai extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:Jianlai';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        //dump(__CLASS__ . '::' . __FUNCTION__);
        //采集某页面所有的图片
        //$data = QueryList::get('http://cms.querylist.cc/bizhi/453.html')->find('img')->attrs('src');
        //$url = "http://www.xmkanshu.com/service/getContent?urbid=%2Fbook_92_0&ctcrid=14&bkid=672340121&crid=14&pg=4&count=2";
        $url = 'https://www.biquge.info/1_1245/';
        //$url = "http://127.0.0.1/";

        $args = [
            'headers' => [
                //'User-Agent' => "Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Mobile Safari/537.36",
                //'Host' => 'www.xmkanshu.com',
                //'Cookies' => 'v=4; WENXUEID=247FE479BAD675EF5A568CFFF766D75E; sajssdk_2015_cross_new_user=1; sensorsdata2015jssdkcross=%7B%22distinct_id%22%3A%221655f7243d6f5-0f68ca4fe36a83-762e6d31-184000-1655f7243d838f%22%2C%22%24device_id%22%3A%221655f7243d6f5-0f68ca4fe36a83-762e6d31-184000-1655f7243d838f%22%2C%22props%22%3A%7B%22%24latest_traffic_source_type%22%3A%22%E7%9B%B4%E6%8E%A5%E6%B5%81%E9%87%8F%22%2C%22%24latest_referrer%22%3A%22%22%2C%22%24latest_referrer_host%22%3A%22%22%2C%22%24latest_search_keyword%22%3A%22%E6%9C%AA%E5%8F%96%E5%88%B0%E5%80%BC_%E7%9B%B4%E6%8E%A5%E6%89%93%E5%BC%80%22%7D%7D; Hm_lvt_7595e1e39ea7e3f08398ec8525b08658=1534904649; Hm_lvt_0a3cb7c2b2ff1ad0353da975a9e19b28=1534904673; Hm_lpvt_7595e1e39ea7e3f08398ec8525b08658=1534904729; Hm_lpvt_0a3cb7c2b2ff1ad0353da975a9e19b28=153490473',
            ],
        ];

        $ql = QueryList::getInstance();
        $data = $ql->get($url, null, $args)->find('.box_con dl dd a')->attrs('href')->all();
        //->find('.box_con > a').attrs('href');

        //print_r(get_class_methods($data));

        //打印结果
        dump($data);
        //echo __CLASS__ . '::' . __FUNCTION__;
    }
}
