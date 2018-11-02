<?php

namespace App\Http\Controllers\Xiaoshuo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Business\Crawler\Search\Director as SearchDirector;
use App\Business\Sites\Zhongheng\Search as ZhonghengSearch;


class SearchController extends Controller
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
    public function search(Request $request)
    {
        $queryString = $request->get('q');
        
        $director = new SearchDirector(new ZhonghengSearch());
        $data = $director->build($queryString);
        
        return view('xiaoshuo.search-list', array('booklist' => $data));
    }
}
