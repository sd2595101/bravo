<?php
namespace App\Business\Helpers\HTTP;

use GuzzleHttp\Client as GHttpClient;
use GuzzleHttp\RequestOptions as GROption;
use Illuminate\Support\Facades\Log;
use App\Business\Utility\AI;

class Client
{
    const BROWSER_PC = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.77 Safari/537.36';
    const BROWSER_SP = 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1';
    
    protected static $ghttpclient = null;
    
    protected $logger = null;
    
    
    
    /**
     * construct
     */
    public function __construct()
    {
        if (is_null(self::$ghttpclient)) {
            self::$ghttpclient = new GHttpClient();
        }
        $this->logger = Log::channel('joblog')->getLogger();
    }

    public function get($url)
    {
        $this->logger->info('http client request start : ' . $url);
        $client = $this->client();
        
        $requestOption = [
            'headers' => self::getHeaders(),
            GROption::ALLOW_REDIRECTS => [
                'max'             => 10,        // allow at most 10 redirects.
                'protocols'       => ['https','http'],
                'track_redirects' => true
            ],
        ];
        
        try {
            
            $response = $client->request('GET', $url, $requestOption);
            $html = $response->getBody()->getContents();
            $this->logger->info('http client request finished [ status : ' . $response->getStatusCode() . ']');
        } catch (Exception $ex) {
            $this->logger->warn('http client request error : ' . $url);
            $this->logger->warn($ex);
            throw $ex;
        }
        
        if ($html) {
            $charset = AI::getCharset($html);
            if ($charset != 'UTF-8') {
                $html = mb_convert_encoding($html, 'UTF-8', $charset);
                return str_replace('charset=' . $charset, 'charset=utf-8', $html);
            }
        }
        
        return $html;
    }
    
    /**
     * 
     * @return GHttpClient
     */
    protected function client()
    {
        return self::$ghttpclient;
    }
    
    public static function getHeaders()
    {
        return [
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'Accept-Encoding' => 'gzip, deflate',
            //'Accept-Language' => 'en-US,en;q=0.9,ja;q=0.8',
            //'Cache-Control' => 'max-age=0',
            'Connection' => 'keep-alive',
            //'Host' => $host,
            //'If-None-Match' => '1540971531|',
            //'Upgrade-Insecure-Requests' => '1',
            'User-Agent' => self::BROWSER_PC,
        ];
    }
}
