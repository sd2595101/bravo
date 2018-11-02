<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Crawler\Book\Director;
use App\Modules\Sites\Zhongheng\Book;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Auth\RedirectsUsers;


class NeedLoginController extends Controller
{
    use RedirectsUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request)
    {
        dump($request);
        //$u = Auth::user();
        return view('home');
        
        
    }
}
