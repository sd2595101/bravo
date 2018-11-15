<?php
namespace App\Business\Jobs;

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

abstract class ZhongHengStoreAbstract implements ShouldQueue
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
        $this->logger->info('############################################################');
        $this->logger->info(__CLASS__ . '::' . __FUNCTION__ . ' start');

        $task = $this->taskRoot;
        $ruleUrl = $task->getAttribute('rule_url');
        $getMaxPage = $task->getAttribute('job_refresh_page') ?? 5;

        for ($page = 1; $page <= $getMaxPage; $page ++) {
            $curl = $this->makePageUrl($ruleUrl, $page);
            // 
            $this->crawlerOnePage($curl);
        }
        $this->logger->info(__CLASS__ . '::' . __FUNCTION__ . ' end');
        $this->logger->info('############################################################');
    }
    
    protected function crawlerOnePage($url)
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
    
    
    
    protected function makePageUrl($ruleUrl, $pageNumber)
    {
        return str_replace('{PageNumber}', $pageNumber, $ruleUrl);
    }
    

    /**
     * The job failed to process.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
        // Send user notification of failure, etc...
        Log::channel('joblog')->getLogger()->error(__CLASS__ . '::' . __FUNCTION__ . ' failed job');
        Log::error($exception);

        // Delete the job from the queue.
        $this->delete();
    }
    
    
    
    abstract protected function crawlerRange();
    abstract protected function crawlerRoules();
    abstract protected function saveData($list);
    
}
