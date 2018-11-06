<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use App\Models\Site as SiteModel;

class SiteController extends Controller
{
    use HasResourceActions;
    
    const HEADER = 'Sites';

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
        $grid = new Grid(new SiteModel);

        $grid->id('ID')->sortable();
        $grid->name('Site name')->sortable();
        $grid->url('Site URL')->link()->sortable();
        $grid->enable('Disabled')->sortable()->using(['1' => '', '0' => 'Disabled'])->badge('red');
        $grid->official('Official')->sortable()->using(['1' => 'Official', '0' => ''])->badge('blue');
        $grid->searcheable('Searcheable')->sortable()->using(['1' => 'Search OK', '0' => ''])->badge('green');
        $grid->created_at('Created at')->sortable();
        $grid->updated_at('Updated at')->sortable();
        
        $grid->filter(function ($filter) {
            $filter->like('name', 'Site name');
            $filter->like('url', 'URL');
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
        $show = new Show(SiteModel::findOrFail($id));

        $show->id('ID');
        $show->name('Site Name');
        $show->url('Site URL');
        $show->enable('Disabled')->using(['1' => '', '0' => 'Disabled'])->badge('red');
        $show->official('Official')->using(['1' => 'Official', '0' => ''])->badge('blue');
        $show->describe('Description')->as(function($c){return nl2br(htmlentities($c));})->unescape();
        $show->searcheable('Searcheable')->using(['1' => 'Search OK', '0' => ''])->badge('green');
        $show->search_url('Search URL');
        $show->search_key('Search KEY');
        $show->search_method('Search Method');
        $show->search_example('Search Example');
        //$show->release_at('Release at');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $grid = Admin::form(SiteModel::class, function(Form $form){
            // Displays the record id
            $form->display('id', 'ID');
            // Add an input box of type text
            $form->url('url', 'Site url')->rules('required');
            $form->text('name', 'Site name')->rules('required');
            $form->switch('official', 'Official?');
            // Add textarea for the describe field
            $form->textarea('describe', 'Description');
            // Add a switch field
            $form->switch('enable', 'Enabled?');
            $form->switch('searcheable', 'Searcheable?');
            $form->url('search_url', 'Search URL');
            $form->text('search_method', 'Search Method');
            $form->text('search_key', 'Search KEY');
            $form->text('search_example', 'Search Example');
            // Add a date and time selection box
            //$form->datetime('release_at', 'release time')->rules('required');
            // Display two time column 
            $form->display('created_at', 'Created time');
            $form->display('updated_at', 'Updated time');
        });
        return $grid;
    }
}
