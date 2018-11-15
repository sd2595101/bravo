<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use App\Models\Book\TaskRoot as MyModel;

class BookTaskRootController extends Controller
{
    use HasResourceActions;
    
    const HEADER = 'Book Task Roots';

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
     * regist task to queue.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function regist($id)
    {
        $idlist = explode(',', $id);
        $status = false;
        
        $num = 0;
        $delay = 0;
        foreach ($idlist as $one) {
            $model = $this->form()->model()->find($one);
            
            if (!$model) {
                continue;
                //throw new Exception('data not found : ' . $one);
            }
            
            // create a job
            $job = $model->createJob();
            
            // dispatch
            if ($delay == 0) {
                $this->dispatchNow($job);
            } else {
                $job->delay(now()->addMinutes($delay));
                $this->dispatch($job);
            }
            
            $num ++;
            $delay = $delay + 1;
        }
        
        
        $status = true;
        $message = trans('task.regist_succeeded') . '['.$num.']';
        
        // make json result Array.
        $data = [
            'status'  => $status,
            'message' => $message,
        ];
        
        return response()->json($data);
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
        $grid = new Grid(new MyModel);
        $grid->tools = new \App\Admin\Grid\TaskTools($grid);

        $grid->id('ID')->sortable();
        $grid->name('Name')->sortable();
        $grid->job_class_name('Job');
        $grid->job_refresh_page('Reading Page');
        $grid->url('URL')->link();
        
        $grid->filter(function ($filter) {
            $filter->like('name', 'Name');
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
        $show = new Show(MyModel::findOrFail($id));

        $show->id('ID');
        $show->name('Name');
        $show->url('URL');
        $show->adeleted('Deleted')->using(['1' => 'Deleted', '0' => ''])->badge('red');
        $show->site_id('Site ID');
        $show->rule_url('Rule URL');
        
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
        $options = array(
            \App\Jobs\ZhongHengStore::class,
            \App\Jobs\ZhongHengStoreDetail::class,
        );
        
        $selectableJobs = [];
        foreach ($options as $o) {
            $selectableJobs[$o] = class_basename($o);
        }
        
        $grid = Admin::form(MyModel::class, function(Form $form) use ($selectableJobs){
            // Displays the record id
            $form->display('id', 'ID');
            $form->select('site_id', 'Site')->options(function(){
                $list = \App\Models\Site::where('enable' ,'=', '1')->get();
                $opts = [];
                foreach ($list as $record) {
                    $key = $record->id;
                    $val = $record->name;
                    //$opts[$key] = $val;
                    $opts[$key] = $key . ' ' . $val;
                }
                return $opts;
            })->rules('required');
            $form->text('name', 'Name')->rules('required');
            // Add an input box of type text
            $form->url('url', 'URL')->rules('required');
//             $form->url('url', 'URL')->rules('required');
            $form->switch('adeleted', 'Deleted?');
            // Add textarea for the describe field
            $form->radio('job_class_name')->options($selectableJobs)->rules('required');
            $form->number('job_refresh_page', 'Reading Page')->default(10)->rules('required');
            $form->text('rule_url');
            $form->text('rule_page_next');
            $form->text('rule_page_prev');
            $form->text('rule_page_last');
            
            // Display two time column 
            $form->display('created_at', 'Created time');
            $form->display('updated_at', 'Updated time');
        });
        return $grid;
    }
}
