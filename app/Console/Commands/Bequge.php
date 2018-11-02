<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use QL\QueryList;

class Bequge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:Bequge';

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
        $ql = QueryList::getInstance();
        $content = $ql->get(config('sites.bequge.top'));
        // $hotlinks = $content->find(config('sites.bequge.hotlist.elem'))->attrs(config('sites.bequge.hotlist.attr'));
        // $otherlinks = $content->find(config('sites.bequge.otherlist.elem'))->attrs(config('sites.bequge.otherlist.attr'));
        // dump($hotlinks);
        // dump($otherlinks);
        dump(config('sites.zhongheng'));
        // foreach ($data as $elem) {
        //     echo 999;exit;
        //     echo $elem->attr('href');
        //     //dump($elem);
        //     exit;
        // }
        
    }
}
