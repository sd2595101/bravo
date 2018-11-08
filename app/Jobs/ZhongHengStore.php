<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use QL\QueryList;
use App\Models\Book\TaskRoot;
use App\Business\Helpers\HTTP\Client as HttpClient;
use App\Models\Book\RawBookStd;

class ZhongHengStore implements ShouldQueue
{

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    /**
     * @var TaskRoot 
     */
    protected $taskRoot = null;

    /**
     * @var Log
     */
    protected $logger = null;

    /**
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache = null;

    /**
     * @var HttpClient
     */
    protected $http = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(TaskRoot $root)
    {
        //
        $this->taskRoot = $root;
        $this->logger = Log::channel('joblog')->getLogger();
        $this->cache = Cache::store('database');
        $this->http = new HttpClient();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $this->logger->info(__CLASS__ . '::' . __FUNCTION__ . ' start');

        $task = $this->taskRoot;
        $ruleUrl = $task->getAttribute('rule_url');

        for ($page = 1; $page <= 5; $page ++) {
            $curl = $this->makePageUrl($ruleUrl, $page);
            // 
            $this->crawlerOnePage($curl);
        }
    }
    
    private function crawlerOnePage($url)
    {
        sleep(1);
        $this->logger->info(__CLASS__ . '::' . __FUNCTION__ . " {$url}");
        // get top page url
        if ($this->cache->has($url)) {
            $html = $this->cache->get($url);
        } else {
            $html = $this->http->get($url);
            $this->cache->set($url, $html, 60 * 24 * 5);
        }
        $ql = QueryList::html($html);
        $result = $ql
            ->range($this->crawlerRange())
            ->rules($this->crawlerRoules())
            ->query()
            ->getData();

        $this->saveData($result);
        
        $this->logger->info(__CLASS__ . '::' . __FUNCTION__ . " End.");
    }
    
    private function saveData($list)
    {
        foreach ($list as $data) {
            $record = RawBookStd::where('author', '=', $data['author'])->where('book_name','=',$data['book_name'])->get();
            if (!$record) {
                RawBookStd::create($data);
            } else {
                RawBookStd::where('author', '=', $data['author'])->where('book_name','=',$data['book_name'])->update($data, ['upsert' => true]);
            }
        }
    }
    
    private function makePageUrl($ruleUrl, $pageNumber)
    {
        return str_replace('{PageNumber}', $pageNumber, $ruleUrl);
    }
    
    private function crawlerRange()
    {
        return '.main_con li';
    }

    private function crawlerRoules()
    {
        return array(
            'cate' => ['.kind a', 'text', '', function($cate) {
                    return \App\Business\Utility\NovelUtility::filterCate($cate);
                }],
            'book_name'         => ['.bookname a', 'text'],
            'book_url_origin'   => ['.bookname a', 'href'],
            'author'            => ['.author a', 'text'],
            'last_chapter_name' => ['.chap a', 'text'],
            'last_chapter_url'  => ['.chap a', 'href'],
            'last_chapter_vip'  => ['.chap em', 'class'],
            'status'            => ['.status', 'text'],
            'daily_click'       => ['.count', 'text'],
            'last_update'       => ['.time', 'text'],
        );
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        // Send user notification of failure, etc...
        Log::channel('joblog')->getLogger()->error(__CLASS__ . '::' . __FUNCTION__ . ' failed job');
        Log::error($exception);

        // Delete the job from the queue.
        $this->delete();
    }
}
