<?php
namespace App\Helpers;

class Markdown
{
    /**
     * @var \HyperDown\Parser 
     */
    private $markdown = null;
    
    public function __construct()
    {
        $this->markdown = new \HyperDown\Parser;
    }
    
    public function parse($text)
    {
        return $this->markdown->makeHtml($text);
    }
}