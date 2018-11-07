<?php

namespace App\Admin\Grid\Tools;

use Encore\Admin\Grid\Tools\BatchAction;

class BatchRegistTask extends BatchAction
{
    public function __construct($title)
    {
        $this->title = $title;
    }

    /**
     * Script of batch delete action.
     */
    public function script()
    {
        $deleteConfirm = trans('task.register_confirm');
        $confirm = trans('admin.confirm');
        $cancel = trans('admin.cancel');

        return <<<EOT

$('{$this->getElementClass()}').on('click', function() {

    var id = {$this->grid->getSelectedRowsName()}().join();

    swal({
        title: "$deleteConfirm",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "$confirm",
        showLoaderOnConfirm: true,
        cancelButtonText: "$cancel",
        preConfirm: function() {
            return new Promise(function(resolve) {
                $.ajax({
                    method: 'post',
                    url: '{$this->resource}/' + id + '/regist',
                    data: {
                        _method:'post',
                        _token:'{$this->getToken()}'
                    },
                    success: function (data) {
                        $.pjax.reload('#pjax-container');

                        resolve(data);
                    }
                });
            });
        }
    }).then(function(result) {
        var data = result.value;
        if (typeof data === 'object') {
            if (data.status) {
                swal(data.message, '', 'success');
            } else {
                swal(data.message, '', 'error');
            }
        }
    });
});

EOT;
    }
}
