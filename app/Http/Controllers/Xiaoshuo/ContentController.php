<?php

namespace App\Http\Controllers\Xiaoshuo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Crawler\Chapter\Director as ChapterDirector;
use App\Modules\Sites\Zhongheng\Chapter;
use App\Modules\Crawler\Book\Director as BookDirector;
use App\Modules\Sites\Zhongheng\Book;

use App\Modules\Crawler\Content\Director as ContentDirector;
//use App\Modules\Sites\Zhongheng\Content as ZhonghengContent;

use App\Modules\Sites\Other\Content as OtherContent;

use App\Modules\Utility\NovelUtility;

class ContentController extends Controller
{

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

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
    public function index($bookid,$chapterid)
    {
        try {
            
            $contentData = NovelUtility::getZHBookInfo2ContentData($bookid,$chapterid);
        } catch (\App\Modules\Crawler\NoCachedChapterException $ex) {
            return redirect(route('book', [$bookid]));
        }
        //dump($contentData);
        ContentDirector::setCache($bookid,$chapterid, $contentData);
        
        if ($contentData['isvip']) {
            try {
                $content = new OtherContent();
                $director = new ContentDirector($content);
                $contentData2 = $director->build($bookid, $chapterid, 'other2');
                $newContent = $contentData2['content'] ?? ['更新失败,请稍后再试'];
                $newOriginUrl = $contentData2['original_url'] ?? '';
                $check = implode('',$contentData2['content'] ?? []);
                if ($check == "") {
                    ContentDirector::clearCache($bookid, $chapterid, 'other2');
                }
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
        //dump($chapters);
        
        return view('xiaoshuo.content', array(
            'info' => $contentData,
            'book' => $bookInfo[0] ?? $bookInfo,
            'prev' => $page['prev'] ? $page['prev'] . '.html' : '',
            'next' => $page['next'] ? $page['next'] . '.html' : '',
        ));
    }
    
    
    public function ijax($bookid,$chapterid)
    {
        
    }
}
