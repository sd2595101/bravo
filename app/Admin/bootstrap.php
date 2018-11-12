<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

Encore\Admin\Form::forget(['map', 'editor']);

// use markdown form grid
Encore\Admin\Grid\Column::extend('markdown', function($text){
    return app('markdown')->parse($text);
});

// use markdown form detail
Encore\Admin\Show\Field::macro('markdown', function(){
    return $this->unescape()->as(function ($text) {
        return app('markdown')->parse($text);
    });
});
    
