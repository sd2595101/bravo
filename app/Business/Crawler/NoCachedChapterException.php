<?php
namespace App\Business\Crawler;

class NoCachedChapterException extends \Exception
{
    protected $bookid;
    
    public function __construct($bookid, $message = '', $code = null)
    {
        parent::__construct($message, $code);
        $this->bookid = $bookid;
    }
    
    public function getBookId()
    {
        return $this->bookid;
    }
}