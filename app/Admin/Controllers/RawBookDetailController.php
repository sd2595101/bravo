<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use App\Models\Book\RawBookDetail as SelfModel;
use App\Models\Book\RawBookStd;
use Encore\Admin\Grid\Row;


class RawBookDetailController extends Controller
{
    use HasResourceActions;
    
    const HEADER = 'Raw Book Details';
    
    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
        ->header(self::HEADER)
        ->description('List')
        ->body($this->grid());
    }
    
    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
        ->header(self::HEADER)
        ->description('view')
        ->body($this->detail($id));
    }
    
    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
        ->header(self::HEADER)
        ->description('edit')
        ->body($this->form()->edit($id));
    }
    
    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
        ->header(self::HEADER)
        ->description('Create')
        ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SelfModel());

        $grid->book_cover('Cover')->image('',80);
        $grid->book_name();
        $grid->author()->sortable();
        $grid->cate()->sortable();
        $grid->status('Status')->badge('blue');
        $grid->last_chapter_name('last chapter');
        $grid->count('Appeal');
        $grid->rows(function(Row $row){
            $chapterLink = $row->column('last_chapter_url');
            $row->column('last_chapter_name', function($column) use ($chapterLink) {
                return '<a href="' .$chapterLink. '" target="_blank">'.$column.'</a>';
            });
            $bookLink = $row->column('book_url_origin');
            $row->column('book_name', function($column) use ($bookLink) {
                return '<a href="' .$bookLink. '" target="_blank">'.$column.'</a>';
            });
        });
        
        $grid->filter(function ($filter) {
            $filter->like('author', 'author');
            $filter->like('book_name', 'Book Name');
            $filter->like('cate', 'Category');
        });
        
        return $grid;
    }
    
    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(SelfModel::findOrFail($id));
        $show->book_cover()->image();
        $show->fields([
            'book_name',
            'author',
            'cate',
            'status',
            'count',
            'laste_update',
            'last_chapter_url',
            'last_chapter_name',
            'last_chapter_vip',
            'updated_at',
        ]);
        
        
        return $show;
    }
    
    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $grid = Admin::form(SelfModel::class, function(Form $form){
            // Displays the record id
            // Add an input box of type text
            $form->display('book_cover')->with(function($item){
                $width = 100;
                $height = 120;
                return "<img src='$item' style='max-width:{$width}px;max-height:{$height}px' class='img img-thumbnail' />";;
            });
            $form->display('book_name');
            $form->display('author');
            $form->display('cate');
            $form->display('status');
            $form->display('count');
            $form->display('laste_update');
            $form->text('last_chapter_url');
            $form->text('last_chapter_name');
            $form->display('updated_at');
        });
        
        return $grid;
    }
}
