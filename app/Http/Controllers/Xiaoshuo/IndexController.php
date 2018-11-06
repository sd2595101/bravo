<?php

namespace App\Http\Controllers\Xiaoshuo;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use BDSpider\BDSpider;
use App\Business\Utility\NovelUtility;

class IndexController extends Controller
{

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * home
     */
    public function index()
    {
        $isLogin = !Auth::guest();
        
        $historys = [];
        
        if ($isLogin) {
            try {
                $historys = $this->readHistory();
            } catch (\App\Business\Crawler\NoCachedChapterException $ex) {
                return redirect(route('book', [$ex->getBookId()]));
            }
        }
        return view('xiaoshuo.index', ['historys' => $historys]);
    }
    
    
    
    private function readHistory()
    {
        $result = [];
        
        $historys = NovelUtility::getBrowsingHistoryList();
        
        foreach($historys as $bookid => $chapterid) {
            $info =  NovelUtility::getHistoryBookInfo($bookid, $chapterid);
            if (!isset($info['book']['title'])) {
                continue;
            }
            $result[] = $info;
        }
        return $result;
    }
}
