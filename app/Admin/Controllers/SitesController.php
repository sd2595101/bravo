<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use App\Models\Sites as SiteModel;

class SitesController extends Controller
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
        $grid->name('Site name');
        $grid->url('Site URL')->link();
        $grid->enable('Enable')->using(['1' => 'Enable', '0' => 'Disable'])->badge('light');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

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
        $show->url('Site URL');
        $show->name('Site Name');
        $show->enable('Enable')->using(['1' => 'Enable', '0' => 'Disable'])->badge();
        $show->describe('Description');
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
            // Add textarea for the describe field
            $form->textarea('describe', 'Description');
            // Add a switch field
            $form->switch('enable', 'Enabled?');
            // Add a date and time selection box
            $form->datetime('release_at', 'release time')->rules('required');
            // Display two time column 
            $form->display('created_at', 'Created time');
            $form->display('updated_at', 'Updated time');
        });
        return $grid;
    }
}
