<?php
return [
    'store' => [
        'zhongheng' => [
            'range'  => '.search-tab .search-result-list',
            'roules' => [
                'image'  => [
                    '.imgbox img',
                    'src'
                ],
                'title'  => [
                    '.tit',
                    'text'
                ],
                'book'   => [
                    '.tit a',
                    'href'
                ],
                'bookid' => [
                    '.tit a',
                    'href',
                    '',
                    function ($href) {
                        $pregPattern = "/http:\/\/book.zongheng.com\/book\/(\d+)\.html/";
                        if (preg_match($pregPattern, $href, $matches)) {
                            return $matches[ 1 ];
                        }
                    }
                ],
                'ulink'   => [
                    '.bookinfo a:first',
                    'href'
                ],
                'uname'   => [
                    '.bookinfo a:first',
                    'text'
                ],
                'clink'   => [
                    '.bookinfo a:last',
                    'href'
                ],
                'cname'   => [
                    '.bookinfo a:last',
                    'text'
                ],
                'keyword' => [
                    '.key-word',
                    'text',
                    '',
                    function ($k) {
                        return explode(' ', str_replace('关键词：', '', $k));
                    }
                ],
                'desc'  => [
                    '.se-result-infos>p',
                    'text',
                    '',
                    function ($v) {
                        return str_replace("\n", '', $v);
                    }
                ]
            ]
        ],
    ],
];
