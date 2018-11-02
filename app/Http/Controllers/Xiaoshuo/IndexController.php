<?php

namespace App\Http\Controllers\Xiaoshuo;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use BDSpider\BDSpider;


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
        if ($isLogin) {
            $this->readHistory();
        }
        return view('xiaoshuo.index');
    }
    
    
    
    private function readHistory()
    {
        //$keyword = '剑来';
        //$result = BDSpider::search($keyword);
        //dump($result);
    }
}
