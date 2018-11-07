<?php

namespace App\Http\Controllers\Xiaoshuo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Business\Crawler\Chapter\Director as ChapterDirector;
use App\Business\Sites\Zhongheng\Chapter;
use App\Business\Crawler\Book\Director as BookDirector;
use App\Business\Sites\Zhongheng\Book;
use App\Business\Crawler\Content\Director as ContentDirector;
use App\Business\Sites\Zhongheng\Content as ZhonghengContent;
use App\Business\Sites\Other\Content as OtherContent;
use App\Business\Utility\NovelUtility;


class ContentController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    
    const OTHER_SITE_KEY = 'other2';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request, $bookid,$chapterid)
    {
        Log::info(__CLASS__ . '::' . __FUNCTION__ . "() - start");
        
        $pjax = $request->header('X-PJAX');
        $view = ($pjax == 'true') ? 'xiaoshuo.pjax.content' : 'xiaoshuo.content';
        
        try {
            $contentData = NovelUtility::getZHBookInfo2ContentData($bookid,$chapterid);
        } catch (\App\Business\Crawler\NoCachedChapterException $ex) {
            return redirect(route('book', [$bookid]));
        }
        if ($contentData['zhvip'] === false) {
            $content = new ZhonghengContent();
            $director = new ContentDirector($content);
            ContentDirector::clearCache($bookid, $chapterid, null);
            $contentData = $director->build($bookid, $chapterid);
        } else if ($contentData['isvip']) {
            ContentDirector::setCache($bookid,$chapterid, $contentData);
            
            try {
                $content = new OtherContent();
                $director = new ContentDirector($content);
                $contentData2 = $director->build($bookid, $chapterid, self::OTHER_SITE_KEY);
                $newContent = $contentData2['content'] ?? ['更新失败,请稍后再试'];
                $newOriginUrl = $contentData2['original_url'] ?? '';
                self::checkContent($bookid, $chapterid, $contentData2);
                $contentData['content'] = $newContent;
                $contentData['original_url'] = $newOriginUrl;
            } catch (\Exception $ex) {
                $contentData['content'] = ['TODO', $ex->getMessage()];
            }
        }
        
        $bookBuilder = new Book();
        $bookDirector = new BookDirector($bookBuilder);
        $bookInfo = $bookDirector->build($bookid);
        
        $chapter = new Chapter();
        $director = new ChapterDirector($chapter);
        $list = $director->build($bookid);
        $chapters = NovelUtility::convertZHChaptersVolumeMerge($list);
        
        $page = $chapters[$chapterid] ?? '';
        NovelUtility::addBrowsingHistory($bookid, $chapterid);
        
        Log::info(__CLASS__ . '::' . __FUNCTION__ . "() - end");
        
        return view($view, array(
            'info' => $contentData,
            'book' => $bookInfo[0] ?? $bookInfo,
            'prev' => $page['prev'] ? $page['prev'] . '.html' : route('chapter',[$bookid]),
            'next' => $page['next'] ? $page['next'] . '.html' : route('chapter',[$bookid]),
        ));
    }
    
    private static function checkContent($bookid,$chapterid, $contentData2)
    {
        $conly = $contentData2['content'] ?? [];
        $conly = is_string($conly) ? [$conly] : $conly;

        $check = implode('',$conly ?? []);
        if ($check == "") {
            ContentDirector::clearCache($bookid, $chapterid, self::OTHER_SITE_KEY);
        }
    }
    
    
    
    public function ijax($bookid,$chapterid)
    {
        
    }
}
