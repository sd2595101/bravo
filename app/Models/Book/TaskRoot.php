<?php

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Model;

class TaskRoot extends Model
{
    //
    protected $table = 'book_task_roots';
    
    protected $fillable = [
        "id",
        "name",
        "url",
        "adeleted",
        "job_class_name",
        "job_refresh_page",
        "rule_url",
        "rule_page_next",
        "rule_page_prev",
        "rule_page_last",
    ];
    
    public function createJob()
    {
        $jobClassName = $this->getAttribute('job_class_name');
        return new $jobClassName($this);
    }
}
