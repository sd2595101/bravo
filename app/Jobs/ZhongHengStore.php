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
        $this->logger->info(__CLASS__ . '::' . __METHOD__ . ' start');

        $task = $this->taskRoot;
        $url = $task->getAttribute('url');
        $ruleUrl = $task->getAttribute('rule_url');

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

        //$info = $result[0] ?? $result;

        dump($result);
        // get top url - html
    }

    private function crawlerRange()
    {
        return '.main_con li';
    }

    private function crawlerRoules()
    {
        return array(
            'cate'             => ['.kind a', ['href' => 'href', 'text' => 'text']],
            'book_name'        => ['.bookname a', ['href' => 'href', 'text' => 'text']],
            'author'           => ['.author a', ['href' => 'href', 'text' => 'text']],
            'last_chapter'     => ['.chap a', ['href' => 'href', 'text' => 'text']],
            'last_chapter_vip' => ['.chap em', ['class' => 'class', 'text' => 'text']],
            'status'           => ['.status', ['text' => 'text']],
            'daily_click'      => ['.count', ['text' => 'text']],
            'update_datetime'  => ['.time', ['text' => 'text']],
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
        Log::channel('joblog')->getLogger()->error(__CLASS__ . '::' . __METHOD__ . ' failed job');
        Log::error($exception);

        // Delete the job from the queue.
        $this->delete();
    }
}
