<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use App\Models\Issue as SelfModel;

class IssueController extends Controller
{
    use HasResourceActions;
    
    const HEADER = 'Issues';

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
        $grid = new Grid(new SelfModel);

        $grid->id('ID')->sortable();
        $grid->subject('Subject')->sortable();
        $grid->status('Status')->badge('blue')->sortable();
        $grid->detail('Detail')->display(function($v){
            return nl2br($v);
        });
        
        $grid->filter(function ($filter) {
            $filter->like('subject', 'Subject');
            $filter->like('status', 'Status');
        });
        
        //$grid->disableActions();
        $grid->disablePagination();
        
        $grid->tools->disableBatchActions();
        $grid->tools->disableFilterButton();
        $grid->tools->disableRefreshButton();
        

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

        $show->id('ID');
        $show->subject('Subject');
        $show->status('Status')->badge('blue');
        $show->detail('Detail');
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
        $grid = Admin::form(SelfModel::class, function(Form $form){
            // Displays the record id
            // Add an input box of type text
            $form->text('subject', 'subject')->rules('required');
            $form->textarea('detail', 'Detail');
            $form->select('status', 'Status')->options(['Open'=>'Open','Closed'=>'Closed'])->rules('required');
            $form->display('created_at', 'Created time');
            $form->display('updated_at', 'Updated time');
        });
        return $grid;
    }
}
