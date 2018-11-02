<?php

use App\Business\Utility\StringUtility;

return [
    /*
     * |--------------------------------------------------------------------------
     * | 笔趣阁
     * |--------------------------------------------------------------------------
     */
    'bequge'    => [
        'top'       => 'https://www.biquge.info/',
        'host'      => 'www.biquge.info',
        'hotlist'   => [
            'elem' => '#hotcontent .item dl dt a',
            'attr' => 'href'
        ],
        'otherlist' => [
            'elem' => '.novelslist .content .top dl dt a, .novelslist .content ul li a, #newscontent ul li span.s2 a',
            'attr' => 'href'
        ],
        'category'  => [],
        'page'      => [
            'list' => [
                'link-elem' => '.box_con dl dd a',
                'link-attr' => 'href'
            ]
        ]
    ],
    'zhongheng' => [
        'top'       => 'http://www.zongheng.com/',
        'book_top'  => 'http://book.zongheng.com/book/{bookid}.html',
        'book_menu' => 'http://book.zongheng.com/showchapter/{bookid}.html',
        'category'  => [
            [
                'http://www.zongheng.com/mianfei/',
                '免费小说'
            ],
            [
                'http://www.zongheng.com/category/1.html',
                '奇幻·玄幻'
            ],
            [
                'http://www.zongheng.com/category/3.html',
                '武侠·仙侠'
            ],
            [
                'http://www.zongheng.com/category/6.html',
                '历史·军事'
            ],
            [
                'http://www.zongheng.com/category/9.html',
                '都市·娱乐'
            ],
            [
                'http://www.zongheng.com/category/21.html',
                '竞技·同人'
            ],
            [
                'http://www.zongheng.com/category/15.html',
                '科幻·游戏'
            ],
            [
                'http://www.zongheng.com/category/18.html',
                '悬疑·灵异'
            ]
        ],
        'rank'      => [
            'top' => [
                'http://book.zongheng.com/rank.html'
            ]
        ],
        'store'     => [
            [
                'http://book.zongheng.com/store/c0/c0/b0/u12/p1/v9/s9/t0/ALL.html',
                '全部作品'
            ],
            [
                'http://book.zongheng.com/store/c1/c0/b0/u12/p1/v9/s9/t0/ALL.html',
                '奇幻玄幻'
            ],
            [
                'http://book.zongheng.com/store/c3/c0/b0/u12/p1/v9/s9/t0/ALL.html',
                '武侠仙侠'
            ],
            [
                'http://book.zongheng.com/store/c6/c0/b0/u12/p1/v9/s9/t0/ALL.html',
                '历史军事'
            ],
            [
                'http://book.zongheng.com/store/c9/c0/b0/u12/p1/v9/s9/t0/ALL.html',
                '都市娱乐'
            ],
            [
                'http://book.zongheng.com/store/c15/c0/b0/u12/p1/v9/s9/t0/ALL.html',
                '科幻游戏'
            ],
            [
                'http://book.zongheng.com/store/c18/c0/b0/u12/p1/v9/s9/t0/ALL.html',
                '悬疑灵异'
            ],
            [
                'http://book.zongheng.com/store/c21/c0/b0/u12/p1/v9/s9/t0/ALL.html',
                '竞技同人'
            ],
            [
                'http://book.zongheng.com/store/c24/c0/b0/u12/p1/v9/s9/t0/ALL.html',
                '评论文集'
            ],
            [
                'http://book.zongheng.com/store/c40/c0/b0/u12/p1/v9/s9/t0/ALL.html',
                '二次元'
            ]
        ],
        'query'     => [
            'url'     => 'http://search.zongheng.com/search/book',
            'key'     => 'keyword',
            'list'    => [
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
                    'desc'      => [
                        '.se-result-infos>p',
                        'text',
                        '',
                        function ($v) {
                            return str_replace("\n", '', $v);
                        }
                    ]
                ]
            ]
        ],
        'book'   => [
            'url' => function ($id) {
                return 'http://book.zongheng.com/book/' . $id . '.html';
                //return 'http://192.168.56.101/book/' . $id . '.html';
            },
            // 'range' => '.box',
            'roules' => [
                'image'       => [
                    '.book-img img',
                    'src'
                ],
                'image-title' => [
                    '.book-img img',
                    'title'
                ],
                'title'       => [
                    '.book-info .book-name',
                    'text'
                ],
                'book'        => [
                    '.all-catalog',
                    'href'
                ],
                'bookid'      => [
                    '.all-catalog',
                    'href',
                    '',
                    function ($href) {
                        $pregPattern = "/http:\/\/book.zongheng.com\/showchapter\/(\d+)\.html/";
                        if (preg_match($pregPattern, $href, $matches)) {
                            return $matches[ 1 ];
                        }
                    }
                ],
                'category_id' => ['.crumb a:last', 'href', '', function($href) {
                        $pregPattern = "/category\/(\d+)\.html/";
                        if (preg_match($pregPattern, $href, $matches)) {
                            return $matches[ 1 ];
                        }
                    }],
                'category_name' => ['.crumb a:last', 'text'],
                'ulink'         => [
                    '.au-name a:first',
                    'href'
                ],
                'uname'         => [
                    '.au-name a:first',
                    'text'
                ],
                'clink'         => [
                    '.book-label a:last',
                    'href'
                ],
                'cname'         => [
                    '.book-label a:last',
                    'text'
                ],
                'length'        => [
                    '.book-info .nums span',
                    'texts'
                ],
                'keyword'       => [
                    '.status .keyword a',
                    'text'
                ],
                'keyword-link'  => [
                    '.status .keyword a',
                    'href'
                ],
                'vote_info'     => [
                    '.vote_info p*',
                    'text',
                    '',
                    function ($item) {
                        $list = explode("\n", $item);
                        $list = array_map('trim', $list);
                        $list = array_diff($list, array(
                            ''
                        ));
                        return array_map(function ($one) {
                                list ($name, $value) = explode('：', $one);
                                return array(
                                    'name'  => $name,
                                    'value' => $value
                                );
                            }, $list);
                    }
                ],
                'desc' => [
                    '.book-dec',
                    'text',
                    '',
                    function ($v) {
                        return str_replace("\n", '', $v);
                    }
                ]
            ]
        ],
        'chapter'      => [
            'url' => function ($id) {
                return 'http://book.zongheng.com/showchapter/' . $id . '.html';
                //return 'http://192.168.56.101/showchapter/' . $id . '.html';
            },
            'range'        => '.volume-list>div',
            'roules'       => [
                'volume' => [
                    '.volume',
                    'text',
                    '-a -em',
                    function ($value) {
                        return StringUtility::trim($value);
                    }
                ],
                'chapter-list' => [
                    '.chapter-list a', [
                        'chapterid'   => 'href',
                        'chaptername' => 'text',
                        'bookid'      => 'href',
                        'href'        => 'href',
                    ],
                    '',
                    function ($list) {
                        return array_map(function ($chapter) {
                                $chapter[ 'chapterid' ] = StringUtility::getZhonghengChapterIdByUrl($chapter[ 'chapterid' ] ?? '');
                                $chapter[ 'bookid' ] = StringUtility::getZhonghengBookIdByChapterUrl($chapter[ 'bookid' ] ?? '');
                                $chapter[ 'chaptername' ] = StringUtility::standardizationChapterTitle($chapter[ 'chaptername' ] ?? '');
                                return $chapter;
                            }, $list);
                    }
                ],
                'chapter-list-vip' => [
                    '.chapter-list li.vip a', [
                        'chapterid'   => 'href',
                        'chaptername' => 'text',
                    ],
                    '',
                    function ($list) {
                        $result = array();
                        array_map(function ($chapter) use (&$result) {
                            $result[] = StringUtility::getZhonghengChapterIdByUrl($chapter[ 'chapterid' ] ?? '');
                        }, $list);
                        return $result;
                    }
                ]
            ]
        ],
        'content' => [
            'url' => function ($book, $chapter) {
                return 'http://book.zongheng.com/chapter/' . $book . '/' . $chapter . '.html';
            },
            'roules'  => [
                'content' => [
                    '.content p',
                    'texts'
                ],
                'volume'  => ['.reader_crumb', 'html', '', function($item) {
                        $parts = explode('&gt;', $item);
                        return trim(array_pop($parts));
                    }],
                'title'   => ['.title_txtbox', 'text'],
                'uname'   => ['.bookinfo a:first', 'text'],
                'isvip'   => [
                    '.vip',
                    'exists',
                ]
            ]
        ],
        
    ],
    '76wx' => [
        'url'     => 'http://www.76wx.com',
        'content' => [
            'roules' => [
                'content'     => [
                    '#content, #contents, #BookText, body',
                    'html',
                    '-div,h1,table,script,center,span',
                    function($html) {
                        $doc = phpQuery::newDocumentHTML($html);
                        $tags = ['div','script'];
                        foreach ($tags as $tag) {
                            pq($doc)->find($tag)->remove();
                        }
                        $html = pq($doc)->htmlOuter();
                        $html = trim($html, "\r\n ");
                        $html = str_replace('顶 点 小 说 x 23 u s．c om', '', $html);
                        $html = str_replace('<br>', "\n", $html);
                        $ps = explode("\n",$html);
                        
                        $ps = array_map(function($pOne){
                            return StringUtility::trim($pOne);
                        }, $ps);
                        $ps = array_diff($ps, ['']);
                        
                        return array_values($ps);
                    }
                ],
                'title'       => ['.bookname>h1', 'text'],
                'chapterlist' => ['.bottem1 a(1)', 'href'],
            ]
        ],
    ],
];
