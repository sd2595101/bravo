<?php
namespace App\Http\Controllers\Xiaoshuo;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use App\Business\Crawler\Book\Director;
use App\Business\Sites\Zhongheng\Book;
//use Illuminate\Support\Facades\Auth;

use App\Models\Book\Book as EntiteBook;
use App\User;

class BookController extends Controller
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
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index($bookid)
    {
        $bookinfodb = EntiteBook::where('bookid', '=', $bookid)->get();
        
        if (count($bookinfodb) == 0) {
            $builder = new Book();
            $director = new Director($builder);
            $info = $director->build($bookid);
            EntiteBook::create($info);
        } else {
            $info = $bookinfodb[0]->toArray();
        }

        return view('xiaoshuo.book', array('book' => $info));
    }
}
