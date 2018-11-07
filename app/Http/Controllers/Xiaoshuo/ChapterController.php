<?php

namespace App\Http\Controllers\Xiaoshuo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Business\Crawler\Chapter\Director;
use App\Business\Sites\Zhongheng\Chapter;
use App\Business\Crawler\Book\Director as BookDirector;
use App\Business\Sites\Zhongheng\Book;


class ChapterController extends Controller
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
    public function index(\Illuminate\Http\Request $request, $bookid)
    {
        $pjax = $request->header('X-PJAX');
        $view = ($pjax == 'true') ? 'xiaoshuo.pjax.chapter' : 'xiaoshuo.chapter';
        $chapter = new Chapter();
        $director = new Director($chapter);
        $list = $director->build($bookid);
        //dump($list);
        $bookBuilder = new Book();
        $bookDirector = new BookDirector($bookBuilder);
        try {
            $info = $bookDirector->build($bookid);
        } catch (Exception $e) {
        }
        
        
        return view($view, array(
            'list' => $list,
            'book' => $info,
        ));
    }
}
