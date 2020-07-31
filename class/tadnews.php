<?php

namespace XoopsModules\Tadnews;

use XoopsModules\Tadtools\CkEditor;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\StarRating;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\SyntaxHighlighter;
use XoopsModules\Tadtools\TadDataCenter;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;

//TadNews物件
/*

//設定種類（news , page ,mixed）
$this->set_news_kind($new_kind="news");

//設定是否開啟選擇模式（應該只用在 list 模式）
$this->set_news_check_mode($mode="0");

//設定編輯器( elrte , ck)
$this->set_news_editor($editor="ck");

//設定是否使用拉動排序工具（應該只用在 list 模式）
$this->set_sort_tool($mode="0");

//設定是否秀出草稿
$this->set_show_enable($enable="1");

//設定欲觀看分類（指定編號、陣列或不指定）
$this->set_view_ncsn($ncsn="");

//設定欲觀看標籤（指定編號、陣列或不指定）
$this->set_view_tag($tag_sn="");

//設定欲觀看文章（指定編號、陣列或不指定）
$this->set_view_nsn($nsn="");

//取得欲觀看文章（指定編號、陣列或不指定）
$this->get_view_nsn();

//設定欲觀看文章
$this->set_show_month($ym="");

//設定欲觀看作者
$this->set_view_uid($uid="");

//設定顯示方式（summary,list,one）
$this->set_show_mode($show_mode="summary");

//設定顯示資料數(int , none)
$this->set_show_num($show_num="10");

//設定管理工具
$this->set_admin_tool($admin_tool=false);

//設定是否列出分類篩選工具
$this->set_news_cate_select($show='0');

//設定是否列出作者篩選工具
$this->set_news_author_select($show='0');

//裁切標題
$this->set_title_length($length=0);

//封面圖片設定
$this->set_cover($show=false,$css="");

//顯示摘要文字（$num=int or "page_break" 根據摘要符號來裁切 or "full"全文 or ''不要）
$this->set_summary($num="",$css="");

//略過文章設定
$this->set_skip_news($num=0);

//設定是否使用評分機制
$this->set_use_star_rating($val=false);

//取得新聞（assign=直接套入樣板，return=傳回陣列）
$this->get_news($mode='assign');

//取得分類新聞（assign=直接套入樣板，return=傳回陣列）
$this->get_cate_news($mode='assign');

//取得分類下拉選單
$this->get_tad_news_cate_option(0,0,$v="",$blank=true,$this_ncsn="",$no_self="0",$not_news=NULL);

//判斷目前登入者在哪些類別中有發表的權利 post,pass,read
$this->chk_user_cate_power($kind="post");

//以流水號取得某筆tad_news資料 $mode=full （不經過xlanguage）
$this->get_tad_news($nsn="",$uid_chk=false,$pass_xlanguage='');

//前置字串 ($mode='all' 不管啟動或關閉，一律顯示，適用於管理界面)
$this->mk_prefix_tag($tag_sn,$mode='');

//計數器
$this->add_counter($nsn);

//解析分類的設定檔
$this->get_setup($setup="");

//tad_news編輯表單
$this->tad_news_form($nsn="",$ncsn="");

//新增資料到tad_news中
$this->insert_tad_news();

//自動取得新排序
$this->get_max_sort($of_ncsn="",$not_news=0);

//以流水號取得某筆tad_news_cate資料
$this->get_tad_news_cate($ncsn="");

//更新tad_news某一筆資料
$this->update_tad_news($nsn="");

//啟動某一篇文章
$this->enable_tad_news($nsn="");

//刪除tad_news某筆資料資料
$tadnews=new Tadnews();
$Tadnews->delete_tad_news($nsn);

 */
class Tadnews
{
    private static $_instance;
    public $kind = 'news'; //news,page,mixed
    public $now;
    public $today;
    public $view_ncsn;
    public $view_tag;
    public $view_nsn;
    public $view_uid;
    public $show_mode = 'summary'; //summary,list,cate,one
    public $show_num = '10';
    public $admin_tool = false;
    public $show_enable = 1;
    public $show_cate_select = 0;
    public $show_author_select = 0;
    public $news_check_mode = 0;
    public $batch_tool = '';
    public $sort_tool = 0;
    public $summary_num = 0;
    public $summary_css = '';
    public $title_length = '';
    public $cover_use = false;
    public $cover_css = '';
    public $skip_news = 0;
    public $use_star_rating = false;
    public $view_month = '';
    public $editor = 'ck';
    public $TadUpFiles;
    public $tadnewsConfig;
    public $tadnewsModule;
    public $module_id;
    public $only_one_ncsn = false;
    public $row = 'row-fluid';
    public $span = 'span';
    public $mini = 'mini';
    public $inline = ' inline';
    public $keyword = '';
    public $start_day = '';
    public $end_day = '';

    //建構函數
    public function __construct()
    {
        global $xoopsConfig;

        xoops_loadLanguage('main', 'tadnews');

        $this->now = date('Y-m-d', xoops_getUserTimestamp(time()));
        $this->today = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));

        $moduleHandler = xoops_getHandler('module');
        $this->tadnewsModule = $moduleHandler->getByDirname('tadnews');
        $this->module_id = $this->tadnewsModule->getVar('mid');
        $configHandler = xoops_getHandler('config');
        $this->tadnewsConfig = $configHandler->getConfigsByCat(0, $this->tadnewsModule->getVar('mid'));

        if ('1' == $this->tadnewsConfig['use_star_rating']) {
            $this->set_use_star_rating(true);
        }
        $this->TadUpFiles = new TadUpFiles('tadnews');
        $this->TadDataCenter = new TadDataCenter('tadnews');
    }

    //覆蓋__clone()方法，禁止克隆
    // private function __clone()
    // {
    // }

    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;

    }

    //是否僅秀出單一分類下的文章
    public function set_only_one_ncsn($set = false)
    {
        $this->only_one_ncsn = $set;
    }

    //設定種類
    public function set_news_kind($new_kind = 'news')
    {
        $this->kind = $new_kind;
    }

    //設定是否開啟選擇模式
    public function set_news_check_mode($mode = '0')
    {
        $this->news_check_mode = $mode;
        $this->batch_tool = $this->batch_tool();
    }

    //設定編輯器
    public function set_news_editor($editor = 'ck')
    {
        $this->editor = $editor;
    }

    //設定是否使用拉動排序工具
    public function set_sort_tool($mode = '0')
    {
        $this->sort_tool = $mode;
    }

    //設定是否秀出草稿
    public function set_show_enable($enable = '1')
    {
        $this->show_enable = $enable;
    }

    //設定欲觀看分類
    public function set_view_ncsn($ncsn = '')
    {
        global $xoopsDB;

        $this->view_ncsn = $ncsn;
        if (!is_array($ncsn) and !empty($ncsn)) {
            $sql = 'select not_news from ' . $xoopsDB->prefix('tad_news_cate') . " where ncsn='{$ncsn}'";
            $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            list($not_news) = $xoopsDB->fetchRow($result);
            if (1 == $not_news) {
                $this->set_news_kind('page');
            }
        }
    }

    //設定欲觀看標籤
    public function set_view_tag($tag_sn = '')
    {
        $this->view_tag = $tag_sn;
        // if (!is_array($tag_sn) and !empty($tag_sn)) {
        //     $sql = 'select not_news from ' . $xoopsDB->prefix('tad_news_cate') . " where ncsn='{$ncsn}'";
        //     $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        //     list($not_news) = $xoopsDB->fetchRow($result);
        //     if (1 == $not_news) {
        //         $this->set_news_kind('page');
        //     }
        // }
    }

    //設定欲觀看文章
    public function set_view_nsn($nsn = '')
    {
        $this->view_nsn = $nsn;
    }

    //設定關鍵字
    public function set_keyword($keyword = '')
    {
        $this->keyword = $keyword;
    }

    //設定起始日期
    public function set_start_day($start_day = '')
    {
        $this->start_day = $start_day;
    }

    //設定結束日期
    public function set_end_day($end_day = '')
    {
        $this->end_day = $end_day;
    }

    //取得欲觀看文章
    public function get_view_nsn()
    {
        return $this->view_nsn;
    }

    //設定欲觀看文章
    public function set_show_month($ym = '')
    {
        $this->view_month = $ym;
    }

    //設定欲觀看作者
    public function set_view_uid($uid = '')
    {
        $this->view_uid = $uid;
    }

    //設定顯示方式，summary,list,cate
    public function set_show_mode($show_mode = 'summary')
    {
        $this->show_mode = $show_mode;
    }

    //設定顯示資料數
    public function set_show_num($show_num = '10')
    {
        $this->show_num = $show_num;
    }

    //設定管理工具
    public function set_admin_tool($admin_tool = false)
    {
        $this->admin_tool = $admin_tool;
    }

    //設定是否列出分類篩選工具
    public function set_news_cate_select($show = '0')
    {
        $this->show_cate_select = $show;
    }

    //設定是否列出作者篩選工具
    public function set_news_author_select($show = '0')
    {
        $this->show_author_select = $show;
    }

    //裁切標題
    public function set_title_length($length = 0)
    {
        $this->title_length = (int) $length;
    }

    //封面圖片設定
    public function set_cover($show = false, $css = '')
    {
        $this->cover_use = $show;
        $this->cover_css = $css;
    }

    //顯示摘要文字
    public function set_summary($num = '', $css = '')
    {
        $this->summary_num = $num;
        $this->summary_css = $css;
    }

    //略過文章設定
    public function set_skip_news($num = 0)
    {
        $this->skip_news = (int) $num;
    }

    //設定是否使用評分機制
    public function set_use_star_rating($val = false)
    {
        $this->use_star_rating = $val;
    }

    //取得圖片
    public function get_news_cover($ncsn = '', $col_name = '', $col_sn = '', $mode = 'big', $style = 'db', $only_url = false, $id = 'cover_pic')
    {
        global $xoopsDB, $xoopsUser;

        $sql = 'select * from ' . $xoopsDB->prefix('tadnews_files_center') . " where `col_name`='{$col_name}' and `col_sn`='{$col_sn}' order by sort";

        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $files_sn, $col_name, $col_sn, $sort, $kind, $file_name, $file_type, $file_size, $description
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $style_set = ('db' === $style) ? $description : $style;
            //die($style_set);

            if (empty($style) and !$only_url) {
                return;
            }

            if ('big' !== $mode) {
                if ($only_url) {
                    return XOOPS_URL . "/uploads/tadnews/image/.thumbs/{$file_name}";
                }
                $img = ('db' === $style) ? "<a href='" . XOOPS_URL . "/modules/tadnews/index.php?ncsn={$ncsn}&nsn=$col_sn'><div id='$id' style='background-image:url(" . XOOPS_URL . "/uploads/tadnews/image/.thumbs/{$file_name});{$style_set}'></div></a>" : "<a href='" . XOOPS_URL . "/modules/tadnews/index.php?ncsn={$ncsn}&nsn=$col_sn' class='thumbnails' style='{$style_set}'><img src='" . XOOPS_URL . "/uploads/tadnews/image/.thumbs/{$file_name}' alt='{$file_name}' title='{$file_name}' style='width: 100%;'></a>";

                return $img;
            }
            if ($only_url) {
                return XOOPS_URL . "/uploads/tadnews/image/{$file_name}";
            }
            $img = ('db' === $style) ? "<a href='" . XOOPS_URL . "/modules/tadnews/index.php?ncsn={$ncsn}&nsn=$col_sn'><div id='$id' style='background-image:url(" . XOOPS_URL . "/uploads/tadnews/image/{$file_name});{$style_set}'></div></a>" : "<a href='" . XOOPS_URL . "/modules/tadnews/index.php?ncsn={$ncsn}&nsn=$col_sn' class='thumbnails' style='{$style_set}'><img src='" . XOOPS_URL . "/uploads/tadnews/image/{$file_name}' alt='{$file_name}' title='{$file_name}' style='width: 100%;'></a>";

            return $img;
        }
    }

    //置頂圖示
    private function get_news_pic($always_top = '', $post_date = '')
    {
        $always_top_pic = ('1' == $always_top) ? "<img src='" . XOOPS_URL . "/modules/tadnews/images/top.gif' alt='" . _TADNEWS_ALWAYS_TOP . "' title='" . _TADNEWS_ALWAYS_TOP . "' hspace=3 align='absmiddle'>" : $this->get_today_pic($post_date);

        return $always_top_pic;
    }

    //今日文章
    private function get_today_pic($post_date = '')
    {
        $today_pic = ($this->now == $post_date) ? "<img src='" . XOOPS_URL . "/modules/tadnews/images/today.gif' alt='" . _TADNEWS_TODAY_NEWS . "' title='" . _TADNEWS_TODAY_NEWS . "' hspace=3 align='absmiddle'>" : '';

        return $today_pic;
    }

    //取得新聞 $mode = 'assign','return','app'
    public function get_news($mode = 'assign', $admin = false)
    {
        global $xoopsDB, $xoopsUser, $isAdmin, $xoopsTpl, $xoTheme;
        if ($admin) {
            $isAdmin = $admin;
        }
        $rating_js = '';
        //設定是否需要高亮度語法
        $SyntaxHighlighter = new SyntaxHighlighter();
        $SyntaxHighlighter->render();

        //取得目前使用者的所屬群組
        if ($xoopsUser) {
            $User_Groups = $xoopsUser->getGroups();
            $now_uid = $xoopsUser->uid();
        } else {
            $User_Groups = [];
            $now_uid = 0;
        }

        $where_news = '';

        //看目前是列出所有文章？還是指定目錄文章？還是單獨一頁？還是一堆指定文章

        //秀出單一篇文章
        if (!empty($this->view_nsn) and is_numeric($this->view_nsn)) {
            //完整內容
            //$this->set_summary('full');
            $this->set_show_num(1);
            $this->set_show_mode('one');
            $this->add_counter($this->view_nsn);

            //找出相關資訊
            $sql2 = 'SELECT ncsn,news_content FROM ' . $xoopsDB->prefix('tad_news') . " WHERE nsn='" . $this->view_nsn . "'";
            $result2 = $xoopsDB->query($sql2) or Utility::web_error($sql2, __FILE__, __LINE__);
            list($ncsn, $news_content) = $xoopsDB->fetchRow($result2);
            if ($redirect = $this->only_url($news_content, $this->view_nsn)) {
                header("location: {$redirect}");
                exit;
            }

            $this->set_view_ncsn($ncsn);

            $sql2 = 'SELECT not_news,nc_title FROM ' . $xoopsDB->prefix('tad_news_cate') . " WHERE ncsn='" . $this->view_ncsn . "'";
            $result2 = $xoopsDB->query($sql2) or Utility::web_error($sql2, __FILE__, __LINE__);
            list($not_news, $show_cate_title) = $xoopsDB->fetchRow($result2);

            $kind = ($not_news) ? 'page' : 'news';
            $this->set_news_kind($kind);

            //分析目前觀看得是新聞還是自訂頁面
            if ('news' === $this->kind) {
                $kind_chk = "and not_news!='1'";
            } elseif ('page' === $this->kind) {
                $kind_chk = "and not_news='1'";
            } else {
                $kind_chk = '';
            }

            $where_cate = " and ncsn='{$this->view_ncsn}'";
            $where_news = " and nsn='{$this->view_nsn}'";

            //秀出一堆指定文章
        } elseif (!empty($this->view_nsn) and is_array($this->view_nsn)) {
            //die(var_export($this->view_nsn));
            //完整內容
            $this->set_summary(0);
            //不限篇數
            $this->set_show_num(0);
            $all_nsn = implode(',', $this->view_nsn);

            //找出相關資訊
            $sql2 = 'select ncsn from ' . $xoopsDB->prefix('tad_news') . " where nsn in($all_nsn)";
            $result2 = $xoopsDB->query($sql2) or Utility::web_error($sql2, __FILE__, __LINE__);
            while (list($ncsn) = $xoopsDB->fetchRow($result2)) {
                $all_ncsn[$ncsn] = $ncsn;
            }
            $this->set_view_ncsn($all_ncsn);
            $all_ncsn = implode(',', $this->view_ncsn);

            //分析目前觀看得是新聞還是自訂頁面
            $kind_chk = '';
            $where_cate = empty($all_ncsn) ? '' : " and ncsn in($all_ncsn)";
            $where_news = " and nsn in($all_nsn)";
            //秀出分類或不指定
        } else {
            //die(var_export($this->view_nsn));

            //分析目前觀看得是新聞還是自訂頁面
            if ('news' === $this->kind) {
                $kind_chk = "and not_news!='1'";
            } elseif ('page' === $this->kind) {
                $kind_chk = "and not_news='1'";
            } else {
                $kind_chk = '';
            }

            //假如沒有指定觀看分類
            if (null === $this->view_ncsn) {
                $where_cate = '';
                //若指定觀看的分類是陣列（限定觀看的分類）
            } elseif (is_array($this->view_ncsn)) {
                $all_ncsn = implode(',', $this->view_ncsn);
                $where_cate = empty($all_ncsn) ? '' : "and ncsn in($all_ncsn)";
                //指定觀看某一個分類
            } elseif ($this->only_one_ncsn or $this->view_ncsn) {
                $where_cate = "and `ncsn` = '{$this->view_ncsn}'";
            } else {
                $where_cate = '';
                //2016-06-28 避免RSS抓不到部份目錄
            }
        }

        //設定是否需要評分工具
        if ($this->use_star_rating) {
            if ('one' === $this->show_mode) {
                $StarRating = new StarRating('tadnews', '10', '', 'simple');
            } else {
                $StarRating = new StarRating('tadnews', '10', 'show', 'simple');
            }
        }

        $ncsn = isset($ncsn) ? $ncsn : '';
        //找指定的分類
        $sql = 'select * from ' . $xoopsDB->prefix('tad_news_cate') . " where 1 $kind_chk $where_cate order by sort";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //$ncsn , $of_ncsn , $nc_title , $enable_group , $enable_post_group , $sort , $cate_pic , $not_news , $setup
        $ncsn_ok = $cates = $cate_setup = $only_title_cate = [];
        while (false !== ($all_cate = $xoopsDB->fetchArray($result))) {
            foreach ($all_cate as $k => $v) {
                $$k = $v;
            }

            //是否僅秀出標題
            $only_title = false !== mb_strpos($setup, 'only_title=1') ? true : false;

            $not_news_arr[$ncsn] = $not_news;

            //只有可讀的分類才納入(或者允許看標題的也可以納入
            $cate_read_power = $this->chk_cate_power($ncsn, $User_Groups, $enable_group, 'read');
            if ($cate_read_power or $only_title) {
                //該使用者可觀看的分類編號陣列
                $ncsn_ok[] = $ncsn;
                //可觀看分類名稱
                $cates[$ncsn] = $nc_title;
                $cate_setup[$ncsn] = $setup;
                if (!$cate_read_power) {
                    $only_title_cate[$ncsn] = $only_title;
                    $only_title_cate_group[$ncsn] = Utility::txt_to_group_name($enable_group, '', ' , ');
                }
            }
        }

        $ncsn_cate_setup = isset($cate_setup[$ncsn]) ? $cate_setup[$ncsn] : '';
        $set = $this->get_setup($ncsn_cate_setup);
        if ('page' === $this->kind) {
            //die(var_dump($cate_setup[$ncsn]));
            //die(var_dump($set));
            //title=0;tool=0;comm=0;breadcrumbs=1
            if ('return' === $mode) {
                foreach ($set as $key => $value) {
                    $main['cate_set_' . $key] = $value;
                }
            } elseif ('app' === $mode) {
            } else {
                foreach ($set as $key => $value) {
                    $xoopsTpl->assign('cate_set_' . $key, $value);
                }
            }
        } else {
            $only_title = isset($set['only_title']) ? $set['only_title'] : 0;
            if ('return' === $mode) {
                $main['cate_only_title'] = $only_title;
            } elseif ('app' === $mode) {
            } else {
                $xoopsTpl->assign('cate_only_title', $only_title);
            }
        }

        if (empty($ncsn_ok)) {
            $where_cate = "and ncsn=''";
        } else {
            $ok_cate = implode(',', $ncsn_ok);

            //假如沒有指定觀看分類
            if (null === $this->view_ncsn) {
                if ('page' === $this->kind) {
                    $where_cate = (empty($ok_cate)) ? 'and 0' : "and ncsn in($ok_cate)";
                } else {
                    $where_cate = (empty($ok_cate)) ? "and ncsn='0'" : "and (ncsn in($ok_cate) or ncsn='0')";
                }
                //若指定觀看的分類是陣列（限定觀看的分類）
            } elseif (is_array($this->view_ncsn)) {
                $where_cate = empty($ok_cate) ? '' : "and ncsn in($ok_cate)";
                //指定觀看某一個分類
            } elseif ($this->only_one_ncsn or $this->view_ncsn) {
                $where_cate = "and `ncsn` = '{$this->view_ncsn}'";
            } else {
                $where_cate = '';
                //2016-06-28 避免RSS抓不到部份目錄
            }
        }

        //假如沒有指定觀看作者
        if (!empty($this->view_uid)) {
            $where_uid = "and uid='{$this->view_uid}'";
        } else {
            $where_uid = '';
        }

        //假如有指定標籤
        if (!empty($this->view_tag)) {
            if (is_array($this->view_tag)) {
                $all_view_tag = implode("', '", $this->view_tag);
                $where_tag = "and prefix_tag in('{$all_view_tag}')";
            } else {
                $where_tag = "and prefix_tag = '{$this->view_tag}'";
            }
        } else {
            $where_tag = '';
        }

        if ('page' === $this->kind) {
            $desc = 'order by ncsn , page_sort';
        } elseif (!empty($this->view_nsn) and is_array($this->view_nsn)) {
            $nsn_order = implode(',', $this->view_nsn);
            $desc = "order by field(`nsn` , $nsn_order)";
        } elseif ('news' === $this->kind) {
            $desc = 'order by always_top desc , start_day desc';
        } else {
            $desc = 'order by always_top desc , start_day desc';
        }

        //判斷是否要檢查日期
        if (!empty($this->view_month)) {
            $date_chk = "and start_day like '{$this->view_month}%'";
        } elseif ($this->admin_tool) {
            $date_chk = '';
        } elseif ($this->start_day and $this->end_day) {
            $date_chk = "and start_day >= '" . $this->start_day . "' and start_day <= '" . $this->end_day . " 23:59:59'";
        } elseif ($this->start_day) {
            $date_chk = "and start_day >= '" . $this->start_day . "'";
        } elseif ($this->end_day) {
            $date_chk = "and start_day <= '" . $this->end_day . " 23:59:59' ";
        } else {
            $date_chk = "and start_day < '" . $this->today . "' and (end_day > '" . $this->today . "' or end_day='0000-00-00 00:00:00') ";
        }

        $and_enable = (1 == $this->show_enable) ? "and enable='1'" : '';

        //判斷是否有關鍵字
        if (!empty($this->keyword)) {
            $and_keyword = "and (`news_title` like '%{$this->keyword}%' or `news_content` like '%{$this->keyword}%')";
        } else {
            $and_keyword = '';
        }

        //die($this->view_month);
        $bar = '';
        if (!empty($this->skip_news)) {
            $limit = (empty($this->show_num) or 'none' === $this->show_num) ? '' : "limit {$this->skip_news} , {$this->show_num}";
            $sql = 'select * from ' . $xoopsDB->prefix('tad_news') . " where 1 $where_news $and_enable $where_uid $where_tag $where_cate $and_keyword $date_chk  $desc $limit";
        } else {
            $limit = empty($this->show_num) ? '10' : $this->show_num;
            $sql = 'select * from ' . $xoopsDB->prefix('tad_news') . " where 1 $where_news $and_enable $where_uid $where_tag $where_cate $and_keyword $date_chk  $desc";
            if (empty($this->view_month) and 'none' !== $this->show_num) {
                //Utility::getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
                $PageBar = Utility::getPageBar($sql, $limit);
                $bar = $PageBar['bar'];
                $sql = $PageBar['sql'];
            }
        }

        // die($sql);

        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //分類篩選工具
        if ($this->show_cate_select) {
            $not_news = ('news' === $this->kind) ? 0 : 1;
            $cate_select = $this->news_cate_select($not_news);
        } else {
            $cate_select = '';
        }

        //作者篩選工具
        if ($this->show_author_select) {
            $author_select = $this->news_author_select();
        } else {
            $author_select = '';
        }
        $all_news = [];
        $i = 0;

        $myts = \MyTextSanitizer::getInstance();
        while (false !== ($news = $xoopsDB->fetchArray($result))) {
            foreach ($news as $k => $v) {
                $$k = $v;
            }

            //判斷本文及所屬分類是否允許該用戶之所屬群組觀看
            $news_read_power = $this->chk_news_power($enable_group, $User_Groups);
            if (!$news_read_power and $uid != $now_uid and !$isAdmin) {
                continue;
            }

            if ($this->use_star_rating) {
                $StarRating->add_rating(XOOPS_URL . '/modules/tadnews/index.php', 'nsn', $nsn);
            }

            $tab_mode = (false !== mb_strpos($news_content, 'Easy-Responsive-Tabs')) ? true : false;

            //新聞資訊列
            $fun = $this->admin_tool($uid, $nsn, $counter, $ncsn, $have_read_group, $enable_post_group);

            $end_day = ('0000-00-00 00:00:00' === $end_day) ? '' : '~ ' . $end_day;
            $enable_txt = (1 == $enable) ? '' : "<span class='badge'>" . _TADNEWS_NEWS_UNABLE . '</span>';

            //製作新聞標題內容，及密碼判斷
            $have_pass = (isset($_SESSION['have_pass'])) ? $_SESSION['have_pass'] : [];

            if ('app' === $mode) {
                $tadnews_files = $this->get_news_files($nsn, 'app');
            } else {
                $file_mode = 'one' === $this->show_mode ? '' : 'small';
                $tadnews_files = $this->get_news_files($nsn, $file_mode);
            }

            if (isset($only_title_cate[$ncsn]) and !empty($only_title_cate[$ncsn])) {
                $news_content = sprintf(_TADNEWS_NEED_LOGIN, $only_title_cate_group[$ncsn]);
                $tadnews_files = '';
            }

            if (!empty($this->summary_num) and is_numeric($this->summary_num)) {
                $news_content = strip_tags($news_content);
                $news_content = str_replace('--summary--', '', $news_content);
                $news_content = trim($news_content);

                //支援xlanguage
                $news_content = $this->xlang($news_content);

                $style = (empty($this->summary_css)) ? '' : "style='{$this->summary_css}'";
                $more = mb_strlen($news_content) <= $this->summary_num ? '' : "... <a href='" . XOOPS_URL . "/modules/tadnews/index.php?ncsn={$ncsn}&nsn={$nsn}' style='font-size: 0.9em;'><i class=\"fa fa-file-text-o\"></i>" . _TADNEWS_MORE . '</a>';

                $news_content = "<div $style>" . mb_substr($news_content, 0, $this->summary_num, _CHARSET) . $more . '</div>';
            } elseif ('page_break' === $this->summary_num) {
                if (preg_match('/--summary--/', $news_content)) {
                    //支援xlanguage
                    $news_content = $this->xlang($news_content);

                    $news_content = str_replace('<p>--summary--</p>', '--summary--', $news_content);
                    $content_arr = explode('--summary--', $news_content);
                } else {
                    $content_arr = explode('<div style="page-break-after: always;"><span style="display: none;">&nbsp;</span></div>', $news_content);
                }
                $more = (empty($content_arr[1])) ? '' : "<p><a href='" . XOOPS_URL . "/modules/tadnews/index.php?ncsn={$ncsn}&nsn={$nsn}' style='font-size: 0.9em;'>" . _TADNEWS_MORE . '...</a></p>';
                $news_content = $content_arr[0] . $more;
            } elseif ('full' === $this->summary_num) {
                $news_content = str_replace('<p>--summary--</p>', '', $news_content);
                $news_content = str_replace('--summary--', '', $news_content);
                //支援xlanguage
                $news_content = $this->xlang($news_content);
            } elseif (empty($this->summary_num)) {
                $news_content = '';
            } else {
                if (preg_match('/' . _SEPARTE2 . '/', $news_content)) {
                    //支援xlanguage
                    $news_content = $this->xlang($news_content);
                    $news_content = str_replace('<p>' . _SEPARTE2 . '</p>', _SEPARTE2, $news_content);
                    $content = explode(_SEPARTE2, $news_content);
                } else {
                    $content = explode(_SEPARTE, $news_content);
                }

                $more = (empty($content[1])) ? '' : "<p><a href='" . XOOPS_URL . "/modules/tadnews/index.php?ncsn={$ncsn}&nsn={$nsn}' style='font-size: 0.9em;'>" . _TADNEWS_MORE . '...</a></p>';
                $news_content = $content[0] . $more;
            }

            //if(!empty($passwd) and !empty($this->summary_num)){
            if (!empty($passwd)) {
                $tadnews_passw = (isset($_POST['tadnews_passwd'])) ? $_POST['tadnews_passwd'] : '';
                require_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
                $XoopsFormHiddenToken = new \XoopsFormHiddenToken();
                $XOOPS_TOKEN = $XoopsFormHiddenToken->render();
                if ($tadnews_passw != $passwd and !in_array($nsn, $have_pass)) {
                    if ('one' === $this->show_mode) {
                        $news_content = "
                        <div class='jumbotron'>
                        <p>" . _TADNEWS_NEWS_NEED_PASSWD . "</p>
                        <form action='" . XOOPS_URL . "/modules/tadnews/index.php' method='post'>
                            <fieldset>
                            <input type='hidden' name='nsn' value='{$nsn}'>
                            <input type='password' name='tadnews_passwd'>
                            $XOOPS_TOKEN
                            <button type='submit' class='btn btn-primary'>" . _TADNEWS_SUBMIT . '</button>
                            </fieldset>
                        </form>
                        </div>';
                    } else {
                        $news_content = '
                        <div>
                            <div>' . _TADNEWS_NEWS_NEED_PASSWD . "</div>
                            <form action='" . XOOPS_URL . "/modules/tadnews/index.php' method='post' style='display:inline'>
                                <fieldset>
                                <input type='hidden' name='nsn' value='{$nsn}'>
                                <input type='password' name='tadnews_passwd'>
                                $XOOPS_TOKEN
                                <button type='submit' class='btn btn-primary'>" . _TADNEWS_SUBMIT . '</button>
                                </fieldset>
                            </form>
                        </div>';
                    }
                    $tadnews_files = '';
                } else {
                    $_SESSION['have_pass'][] = $nsn;
                }
            }

            $have_read_chk = $this->have_read_chk($have_read_group, $nsn, $mode);

            $uid_name = \XoopsUser::getUnameFromId($uid, 1);
            $uid_name = (empty($uid_name)) ? \XoopsUser::getUnameFromId($uid, 0) : $uid_name;

            $news_title = (empty($news_title)) ? _TADNEWS_NO_TITLE : $news_title;
            $cate_name = (empty($cates[$ncsn])) ? _TADNEWS_NO_CATE : $cates[$ncsn];

            $post_date = mb_substr($start_day, 0, 10);
            $today_pic = $this->get_news_pic($always_top, $post_date);
            if ('summary' === $this->show_mode or 'one' === $this->show_mode) {
                $need_sign = (!empty($have_read_group)) ? XOOPS_URL . '/modules/tadnews/images/sign_bg.png' : '';
            } else {
                $need_sign = (!empty($have_read_group)) ? XOOPS_URL . '/modules/tadnews/images/sign_s.png' : '';
            }
            $g_txt = Utility::txt_to_group_name($enable_group, _TADNEWS_ALL_OK);

            $show_admin_tool = ($this->admin_tool) ? 1 : 0;

            $link_page = ('1' == $not_news_arr[$ncsn]) ? 'page.php' : 'index.php';

            $chkbox = ($this->news_check_mode) ? "<input type='checkbox' name='nsn_arr[]' value='$nsn'> " : '';

            if (!empty($this->title_length)) {
                $news_title = mb_substr($news_title, 0, $this->title_length, _CHARSET);
                $news_title .= '...';
            }

            if ($this->cover_use) {
                $pic = $this->get_news_cover($ncsn, 'news_pic', $nsn, 'big', $this->cover_css, null, 'demo_cover_pic');
            } else {
                $pic = '';
            }

            $image_big = $this->get_news_cover($ncsn, 'news_pic', $nsn, 'big', null, true);
            $image_thumb = $this->get_news_cover($ncsn, 'news_pic', $nsn, 'thumb', null, true);

            if ($this->use_star_rating) {
                $rating_js = $StarRating->render();
            }

            $prefix_tag = $this->mk_prefix_tag($prefix_tag);
            if ('app' === $mode) {
                $prefix_tag = strip_tags($prefix_tag);
            }

            $back_news = $back_news_link = $back_news_title = $next_news_link = $next_news_title = $next_news = $push = $facebook_comments = '';

            if (!empty($this->view_nsn) and is_numeric($this->view_nsn)) {
                $nsnsort = $this->news_sort($nsn);

                if (isset($nsnsort['back']) and !empty($nsnsort['back']['nsn'])) {
                    //支援xlanguage
                    $nsnsort['back']['title'] = $this->xlang($nsnsort['back']['title']);
                    //$title=xoops_substr($nsnsort['back']['title'], 0, 30);
                    $title = mb_substr($nsnsort['back']['title'], 0, 20, _CHARSET) . '...';
                    $date = mb_substr($nsnsort['back']['date'], 5);
                    $back_news_link = XOOPS_URL . "/modules/tadnews/{$link_page}?ncsn={$nsnsort['back']['ncsn']}&nsn={$nsnsort['back']['nsn']}";
                    $back_news_title = ('page' === $this->kind) ? $title : "{$date} {$title}";
                }

                if (isset($nsnsort['next']) and !empty($nsnsort['next']['nsn'])) {
                    //支援xlanguage
                    $nsnsort['next']['title'] = $this->xlang($nsnsort['next']['title']);

                    //$title=xoops_substr($nsnsort['next']['title'], 0, 30);
                    $title = mb_substr($nsnsort['next']['title'], 0, 20, _CHARSET) . '...';
                    $date = mb_substr($nsnsort['next']['date'], 5);

                    $next_news_link = XOOPS_URL . "/modules/tadnews/{$link_page}?ncsn={$nsnsort['next']['ncsn']}&nsn={$nsnsort['next']['nsn']}";
                    $next_news_title = ('page' === $this->kind) ? $title : "{$date} {$title}";
                }

                $facebook_comments = ('1' == $this->tadnewsConfig['facebook_comments_width']) ? Utility::facebook_comments($this->tadnewsConfig['facebook_comments_width'], 'tadnews', 'index.php', 'nsn', $nsn) : '';
                $push = Utility::push_url($this->tadnewsConfig['use_social_tools']);
            }

            if ('app' !== $mode) {
                $all_news[$i]['facebook_comments'] = $facebook_comments;
                $all_news[$i]['fun'] = $fun;
                $all_news[$i]['push'] = $push;
                $all_news[$i]['show_admin_tool'] = $show_admin_tool;
                $all_news[$i]['chkbox'] = $chkbox;
                $all_news[$i]['today_pic'] = $today_pic;
                $all_news[$i]['link_page'] = $link_page;
                $all_news[$i]['passwd'] = $passwd;
                $all_news[$i]['enable_txt'] = $enable_txt;
                $all_news[$i]['pic'] = $pic;

                if ($this->use_star_rating) {
                    $all_news[$i]['star'] = "<div id='rating_nsn_{$nsn}'></div>";
                } else {
                    $all_news[$i]['star'] = '';
                }
            } else {
                $all_news[$i]['passwd'] = !empty($passwd) ? true : false;
            }
            $all_news[$i]['nsn'] = $nsn;
            $all_news[$i]['ncsn'] = $ncsn;
            $all_news[$i]['cate_name'] = $cate_name;
            $all_news[$i]['post_date'] = $post_date;
            $all_news[$i]['prefix_tag'] = $prefix_tag;
            $all_news[$i]['need_sign'] = $need_sign;
            $all_news[$i]['news_title'] = $myts->htmlSpecialChars($news_title);
            $all_news[$i]['uid'] = $uid;
            $all_news[$i]['uid_name'] = $uid_name;
            $all_news[$i]['counter'] = $counter;
            $all_news[$i]['content'] = $myts->displayTarea($news_content, 1, 1, 1, 1, 0);
            $all_news[$i]['g_txt'] = $g_txt;
            $all_news[$i]['files'] = $tadnews_files;
            $all_news[$i]['enable'] = $enable;
            $all_news[$i]['have_read_chk'] = $have_read_chk;
            $all_news[$i]['back_news_link'] = $back_news_link;
            $all_news[$i]['back_news_title'] = $back_news_title;
            $all_news[$i]['next_news_link'] = $next_news_link;
            $all_news[$i]['next_news_title'] = $next_news_title;
            $all_news[$i]['image_big'] = $image_big;
            $all_news[$i]['image_thumb'] = $image_thumb;
            $all_news[$i]['not_news'] = $not_news_arr[$ncsn];

            $i++;
        }

        $ui = 1 == $this->sort_tool ? true : false;
        $jquery = Utility::get_jquery($ui);

        if ('return' === $mode) {
            // $main['jquery'] = $jquery;
            $main['page'] = $all_news;
            $main['show_admin_tool_title'] = $this->admin_tool;
            $main['show_sort_tool'] = $this->sort_tool;
            $main['action'] = $_SERVER['PHP_SELF'];
            $main['batch_tool'] = $this->batch_tool;
            $main['del_js'] = $this->del_js();
            $main['cate_select'] = $cate_select;
            $main['author_select'] = $author_select;
            $main['bar'] = $bar;
            if ($this->use_star_rating) {
                $main['rating_js'] = $rating_js;
            }
            if (is_numeric($this->view_ncsn)) {
                $main['show_cate_title'] = $show_cate_title;
            }

            return $main;
        } elseif ('app' === $mode) {
            return $all_news;
        }

        $xoopsTpl->assign('jquery', $jquery);
        $xoopsTpl->assign('page', $all_news);
        $xoopsTpl->assign('show_admin_tool_title', $this->admin_tool);
        $xoopsTpl->assign('show_sort_tool', $this->sort_tool);
        $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
        $xoopsTpl->assign('batch_tool', $this->batch_tool);
        $xoopsTpl->assign('del_js', $this->del_js());
        $xoopsTpl->assign('cate_select', $cate_select);
        $xoopsTpl->assign('author_select', $author_select);
        $xoopsTpl->assign('bar', $bar);
        if ($this->use_star_rating) {
            $xoopsTpl->assign('rating_js', $rating_js);
        }
        if (is_numeric($this->view_ncsn)) {
            $xoopsTpl->assign('show_cate_title', $show_cate_title);
            $xoopsTpl->assign('nc_title', $show_cate_title);
        }

        if (isset($all_news[0]['news_title'])) {
            if (is_object($xoTheme)) {
                $xoTheme->addMeta('meta', 'keywords', $all_news[0]['news_title']);
                $xoTheme->addMeta('meta', 'description', strip_tags($all_news[0]['content']));
            } else {
                $xoopsTpl->assign('xoops_meta_keywords', 'keywords', $all_news[0]['news_title']);
                $xoopsTpl->assign('xoops_meta_description', strip_tags($all_news[0]['content']));
            }

            $xoopsTpl->assign('fb_title', $all_news[0]['news_title']);
            $xoopsTpl->assign('fb_description', strip_tags($all_news[0]['content']));
            $xoopsTpl->assign('fb_image', $all_news[0]['image_big']);
            $xoopsTpl->assign('xoops_pagetitle', $all_news[0]['news_title']);
        }
    }

    //取得分類路徑
    public function get_cate_path($ncsn = '', $sub = false)
    {
        global $xoopsDB;

        if (!$sub) {
            $home[_TAD_TO_MOD] = XOOPS_URL . '/modules/tadnews/index.php';
        } else {
            $home = [];
        }

        $sql = 'select nc_title,of_ncsn,not_news from ' . $xoopsDB->prefix('tad_news_cate') . " where ncsn='{$ncsn}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($nc_title, $of_ncsn, $not_news) = $xoopsDB->fetchRow($result);

        $opt_sub = (!empty($of_ncsn)) ? $this->get_cate_path($of_ncsn, true) : '';

        $opt = $path = [];

        if (!empty($nc_title)) {
            $page = ('1' == $not_news) ? 'page.php' : 'index.php';
            $opt[$nc_title] = XOOPS_URL . "/modules/tadnews/{$page}?ncsn=$ncsn";
        }
        if (is_array($opt_sub)) {
            $path = array_merge($home, $opt_sub, $opt);
        } elseif (is_array($opt)) {
            $path = array_merge($home, $opt);
        } else {
            $path = $home;
        }

        return $path;
    }

    //取得分類新聞
    public function get_cate_news($mode = 'assign', $include_sub_cate = false)
    {
        global $xoopsDB, $xoopsTpl, $xoopsUser;

        $pic_w = $this->tadnewsConfig['cate_pic_width'] + 10;

        $SyntaxHighlighter = new SyntaxHighlighter();
        $SyntaxHighlighter->render();

        //取得目前使用者的所屬群組
        if ($xoopsUser) {
            $User_Groups = $xoopsUser->getGroups();
            $now_uid = $xoopsUser->uid();
        } else {
            $User_Groups = [];
            $now_uid = 0;
        }

        $where_news = '';

        //分析目前觀看得是新聞還是自訂頁面
        if ('news' === $this->kind) {
            $kind_chk = "and not_news!='1'";
        } elseif ('page' === $this->kind) {
            $kind_chk = "and not_news='1'";
        } else {
            $kind_chk = '';
        }

        if (is_array($this->view_ncsn)) {
            $show_ncsn = implode(',', $this->view_ncsn);
            $and_cate = empty($show_ncsn) ? '' : "and ncsn in({$show_ncsn})";
        } elseif ('' == $this->view_ncsn) {
            $and_cate = '';
        } else {
            $and_cate = "and ncsn={$this->view_ncsn}";
        }

        $sql = 'select ncsn,nc_title,enable_group,enable_post_group,cate_pic,setup from ' . $xoopsDB->prefix('tad_news_cate') . " where 1  $and_cate $kind_chk order by sort";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $i = 0;
        $only_title = false;
        $only_title_cate = [];
        while (list($ncsn, $nc_title, $enable_group, $enable_post_group, $cate_pic, $setup) = $xoopsDB->fetchRow($result)) {
            //只有可讀的分類才納入
            $cate_read_power = $this->chk_cate_power($ncsn, $User_Groups, $enable_group, 'read');

            if (!$cate_read_power) {
                //是否僅秀出標題
                $only_title = false !== mb_strpos($setup, 'only_title=1') ? true : false;
                $only_title_cate[$ncsn] = $only_title;
                $only_title_cate_group[$ncsn] = Utility::txt_to_group_name($enable_group, '', ' , ');
                if (!$only_title) {
                    // die($nc_title);
                    continue;
                }
            }

            $pic = (empty($cate_pic)) ? XOOPS_URL . '/modules/tadnews/images/no_cover.png' : XOOPS_URL . "/uploads/tadnews/cate/{$cate_pic}";

            $and_enable = (1 == $this->show_enable) ? "and enable='1'" : '';

            $sql2 = ('page' === $this->kind) ? 'select * from ' . $xoopsDB->prefix('tad_news') . " where ncsn='{$ncsn}' $and_enable order by page_sort" : 'select * from ' . $xoopsDB->prefix('tad_news') . " where ncsn='{$ncsn}' $and_enable and start_day < '" . $this->today . "' and (end_day > '" . $this->today . "' or end_day='0000-00-00 00:00:00') order by always_top desc , start_day desc limit 0," . $this->show_num;

            $result2 = $xoopsDB->query($sql2) or Utility::web_error($sql2, __FILE__, __LINE__);

            $j = 0;
            $subnews = [];
            $only_title_cate = [];

            $myts = \MyTextSanitizer::getInstance();
            while (false !== ($news = $xoopsDB->fetchArray($result2))) {
                foreach ($news as $k => $v) {
                    $$k = $v;
                }

                if (!empty($passwd)) {
                    require_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
                    $XoopsFormHiddenToken = new \XoopsFormHiddenToken();
                    $XOOPS_TOKEN = $XoopsFormHiddenToken->render();

                    $tadnews_passw = (isset($_POST['tadnews_passwd'])) ? $_POST['tadnews_passwd'] : '';
                    if ($tadnews_passw != $passwd and !in_array($nsn, $have_pass)) {
                        if ('one' === $this->show_mode) {
                            $news_content = "
                        <div class='jumbotron'>
                        <p>" . _TADNEWS_NEWS_NEED_PASSWD . "</p>
                        <form action='" . XOOPS_URL . "/modules/tadnews/index.php' method='post'>
                                <fieldset>
                                <input type='hidden' name='nsn' value='{$nsn}'>
                                <input type='password' name='tadnews_passwd'>
                                $XOOPS_TOKEN
                                <button type='submit' class='btn btn-primary'>" . _TADNEWS_SUBMIT . '</button>
                                </fieldset>
                        </form>
                        </div>';
                        } else {
                            $news_content = '
                        <div>
                        <div>' . _TADNEWS_NEWS_NEED_PASSWD . "</div>
                        <form action='" . XOOPS_URL . "/modules/tadnews/index.php' method='post' style='display:inline'>
                            <fieldset>
                            <input type='hidden' name='nsn' value='{$nsn}'>
                            <input type='password' name='tadnews_passwd'>
                            $XOOPS_TOKEN
                            <button type='submit' class='btn btn-primary'>" . _TADNEWS_SUBMIT . '</button>
                            </fieldset>
                        </form>
                        </div>';
                        }
                        $tadnews_files = '';
                    } else {
                        $_SESSION['have_pass'][] = $nsn;
                    }
                } elseif (isset($only_title_cate[$ncsn]) and !empty($only_title_cate[$ncsn])) {
                    // die('enable_group:' . $enable_group);
                    $news_content = sprintf(_TADNEWS_NEED_LOGIN, $only_title_cate_group[$ncsn]);
                }

                // $news_read_power = $this->chk_news_power($enable_group, $User_Groups);
                // if (!$news_read_power) {
                //     continue;
                // }

                if (is_numeric($this->summary_num) and !empty($this->summary_num) and empty($passwd)) {
                    $news_content = strip_tags($news_content);
                    $style = (empty($this->summary_css)) ? '' : "style='{$this->summary_css}'";
                    //支援xlanguage
                    $news_content = $this->xlang($news_content);

                    $content = "<div $style>" . mb_substr($news_content, 0, $this->summary_num, _CHARSET) . '...</div>';
                } else {
                    $content = '';
                }
                if ('summary' === $this->show_mode or 'one' === $this->show_mode) {
                    $need_sign = (!empty($have_read_group)) ? XOOPS_URL . '/modules/tadnews/images/sign_bg.png' : '';
                } else {
                    $need_sign = (!empty($have_read_group)) ? XOOPS_URL . '/modules/tadnews/images/sign_s.png' : '';
                }

                $news_title = (empty($news_title)) ? _TADNEWS_NO_TITLE : $news_title;

                $subnews[$j]['content'] = $myts->displayTarea($content, 1, 1, 1, 1, 0);
                $subnews[$j]['post_date'] = mb_substr($start_day, 0, 10);
                $subnews[$j]['always_top_pic'] = $this->get_news_pic($always_top, mb_substr($start_day, 0, 10));
                $subnews[$j]['prefix_tag'] = $this->mk_prefix_tag($prefix_tag);
                $subnews[$j]['nsn'] = $nsn;
                $subnews[$j]['news_title'] = $myts->htmlSpecialChars($news_title);
                $subnews[$j]['counter'] = $counter;
                $subnews[$j]['need_sign'] = $need_sign;
                $subnews[$j]['files'] = $this->get_news_files($nsn, 'small');
                $j++;
            }
            $all_news[$i]['pic_w'] = $pic_w;
            $all_news[$i]['show_pic'] = $this->cover_use;
            $all_news[$i]['pic'] = $pic;
            $all_news[$i]['nc_title'] = $nc_title;
            $all_news[$i]['ncsn'] = $ncsn;
            $all_news[$i]['news'] = $subnews;
            $all_news[$i]['rowspan'] = $j + 1;

            // if ($include_sub_cate) {
            //     $this->get_cate_news('return', $include_sub_cate);
            // }

            $i++;
        }

        if ('return' === $mode) {
            $main['all_news'] = $all_news;

            return $main;
        }
        $xoopsTpl->assign('all_news', $all_news);
    }

    //判斷本文之所屬分類是否允許該用戶之所屬群組觀看或發佈
    private function chk_cate_power($ncsn = '', $User_Groups = '', $enable_group = '', $kind = 'read')
    {
        global $xoopsDB, $xoopsUser;

        if (!empty($enable_group)) {
            $ok = false;
            $cate_enable_group = explode(',', $enable_group);
            if (in_array('', $cate_enable_group)) {
                return true;
            }

            foreach ($User_Groups as $gid) {
                $gid = (int) $gid;

                if (in_array($gid, $cate_enable_group) or 1 == $gid) {
                    return true;
                }
            }
        } else {
            return true;
        }

        return false;
    }

    //判斷本文是否允許該用戶之所屬群組觀看或發佈
    private function chk_news_power($enable_group = '', $User_Groups = '')
    {
        global $xoopsDB, $xoopsUser;

        if (empty($enable_group)) {
            return true;
        }

        $news_enable_group = array_map('intval', explode(',', $enable_group));
        foreach ($User_Groups as $gid) {
            $gid = (int) $gid;
            if (in_array($gid, $news_enable_group)) {
                return true;
            }
        }

        return false;
    }

    //新聞編輯工具
    private function admin_tool($uid, $nsn, $counter = '', $ncsn = '', $have_read_group = '', $enable_post_group = '')
    {
        global $xoopsUser;

        if ($xoopsUser) {
            $uuid = $xoopsUser->uid();
            $isAdmin = $xoopsUser->isAdmin($this->module_id);
            $User_Groups = $xoopsUser->getGroups();
        } else {
            $uuid = $isAdmin = '';
        }

        $btn_xs = (isset($_SESSION['bootstrap']) and 4 == $_SESSION['bootstrap']) ? 'btn-sm' : 'btn-xs';

        $this->TadDataCenter->set_col('nsn', $nsn);
        $tab_arr = $this->TadDataCenter->getData();
        $tab_sort_btn = !empty($tab_arr) ? "<a href='" . XOOPS_URL . "/modules/tadnews/page.php?op=tabs_sort&ncsn=$ncsn&nsn=$nsn' class='btn btn-info $btn_xs' style='font-weight:normal;'><i class='fa fa-sort'></i> " . _TADNEWS_TABS_SORT . '</a>' : '';

        $edit_cate = '';
        if (!empty($ncsn)) {
            $edit_cate = ('page' === $this->kind) ? "<a href='" . XOOPS_URL . "/modules/tadnews/admin/page.php?op=modify_page_cate&ncsn=$ncsn' class='btn btn-warning $btn_xs' style='font-weight:normal;'><i class='fa fa-folder-open-o'></i> " . _TADNEWS_EDIT_CATE . "</a>{$tab_sort_btn}" : "<a href='" . XOOPS_URL . "/modules/tadnews/admin/main.php?op=modify_news_cate&ncsn=$ncsn' class='btn btn-warning $btn_xs' style='font-weight:normal;'><i class='fa fa-folder-open-o'></i> " . _TADNEWS_EDIT_CATE . '</a>';
        }

        $signbtn = '';
        if (!empty($have_read_group)) {
            $signbtn = "<a href='" . XOOPS_URL . "/modules/tadnews/index.php?op=list_sign&ncsn={$ncsn}&nsn=$nsn' class='btn btn-info $btn_xs' style='font-weight:normal;'><i class='fa fa-pencil'></i> " . _TADNEWS_DIGN_LIST . '</a>';
        }

        $news_post_power = $this->chk_news_power($enable_post_group, $User_Groups);

        $admin_fun = ($news_post_power or $uid == $uuid or $isAdmin) ? "
        $signbtn
        <a href='" . XOOPS_URL . "/modules/tadnews/post.php' class='btn btn-primary $btn_xs' style='font-weight:normal;'><i class='fa fa-plus-circle'></i> " . _TADNEWS_ADD . "</a>
        <a href=\"javascript:delete_tad_news_func($nsn);\" class='btn btn-danger $btn_xs' style='font-weight:normal;'><i class='fa fa-trash'></i> " . _TADNEWS_DEL . "</a>
        $edit_cate
        <a href='" . XOOPS_URL . "/modules/tadnews/post.php?op=tad_news_form&ncsn={$ncsn}&nsn=$nsn' class='btn btn-warning $btn_xs' style='font-weight:normal;'><i class='fa fa-pencil'></i> " . _TADNEWS_EDIT . '</a>' : '';

        $bbcode = (isset($this->tadnewsConfig['show_bbcode']) and '1' == $this->tadnewsConfig['show_bbcode']) ? "<a href='" . XOOPS_URL . "/modules/tadnews/index.php?ncsn={$ncsn}&nsn={$nsn}&bb=1' class='btn btn-success $btn_xs' style='font-weight:normal;'>BBCode</a>" : '';

        $fun = "
        <div class='btn-group'>
        $admin_fun
        $bbcode
        </div>";

        return $fun;
    }

    //刪除的js
    private function del_js()
    {

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('delete_tad_news_func', "{$_SERVER['PHP_SELF']}?op=delete_tad_news&nsn=", 'nsn');

        // $js = "<script>
        // function delete_tad_news_func(nsn){
        //   var sure = window.confirm('" . _TADNEWS_SURE_DEL . "');
        //   if (!sure)  return;
        //   location.href=\"{$_SERVER['PHP_SELF']}?op=delete_tad_news&nsn=\" + nsn;
        // }
        // </script>";

        // return $js;
    }

    //列出所有作者的下拉選單
    private function news_author_select()
    {
        global $xoopsDB;
        $sql = 'SELECT uid FROM ' . $xoopsDB->prefix('tad_news') . ' GROUP BY uid';
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $opt = _TADNEWS_SHOW_AUTHOR_NEWS . "
    <select onChange=\"window.location.href='{$_SERVER['PHP_SELF']}?show_uid='+this.value\">
    <option value=''></option>";
        while (list($uid) = $xoopsDB->fetchRow($result)) {
            $uid_name = \XoopsUser::getUnameFromId($uid, 1);
            $uid_name = (empty($uid_name)) ? \XoopsUser::getUnameFromId($uid, 0) : $uid_name;
            $selected = ($this->view_uid == $uid) ? 'selected' : '';
            $opt .= "<option value='$uid' $selected>$uid_name</option>";
        }
        $opt .= '</select>';

        return $opt;
    }

    //列出分類篩選工具
    private function news_cate_select($not_news = '')
    {
        $cate_select = $this->get_tad_news_cate_option(0, 0, $this->view_ncsn, true, 0, '1', $not_news);
        $form = _TADNEWS_SHOW_CATE_NEWS . "
        <select onChange=\"window.location.href='{$_SERVER['PHP_SELF']}?ncsn='+this.value\">
          $cate_select
        </select>";

        return $form;
    }

    //取得分類下拉選單
    public function get_tad_news_cate_option($of_ncsn = 0, $level = 0, $v = '', $blank = true, $this_ncsn = '', $no_self = '0', $not_news = null)
    {
        global $xoopsDB, $xoopsUser;
        if ($xoopsUser) {
            $isAdmin = $xoopsUser->isAdmin($this->module_id);
        } else {
            $isAdmin = false;
        }

        $ok_cat = $this->chk_user_cate_power();

        $and_not_news = (null === $not_news or '' === $not_news) ? '' : "and not_news='{$not_news}'";

        if ($isAdmin) {
            $left = $level * 10;
            $level += 1;

            $option = ($of_ncsn or !$isAdmin or false === $blank) ? '' : "<option value='0'></option>";

            // '' == $option;
            $sql = 'select ncsn,nc_title,not_news from ' . $xoopsDB->prefix('tad_news_cate') . " where of_ncsn='{$of_ncsn}' $and_not_news order by sort";
            $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            if ($_GET['test'] == '1') {
                die($sql);
            }

            while (list($ncsn, $nc_title, $not_news) = $xoopsDB->fetchRow($result)) {
                $ncsn = (int) $ncsn;
                if (!in_array($ncsn, $ok_cat)) {
                    continue;
                }

                if ('1' == $no_self and $this_ncsn == $ncsn) {
                    continue;
                }

                $selected = ($v == $ncsn) ? 'selected' : '';
                $color = ('1' == $not_news) ? 'red' : 'black';
                $option .= "<option value='{$ncsn}' style='padding-left: {$left}px;color:{$color};' $selected>{$nc_title}</option>";
                $option .= $this->get_tad_news_cate_option($ncsn, $level, $v, true, $this_ncsn, $no_self, $not_news);
            }
        } else {
            $all_ncsn = implode(',', $ok_cat);
            $sql = 'select ncsn,nc_title,not_news from ' . $xoopsDB->prefix('tad_news_cate') . " where ncsn in($all_ncsn) $and_not_news order by sort";

            $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            while (list($ncsn, $nc_title, $not_news) = $xoopsDB->fetchRow($result)) {
                $ncsn = (int) $ncsn;
                if (!in_array($ncsn, $ok_cat)) {
                    continue;
                }

                if ('1' == $no_self and $this_ncsn == $ncsn) {
                    continue;
                }

                $selected = ($v == $ncsn) ? 'selected' : '';
                $color = ('1' == $not_news) ? 'red' : 'black';
                $option .= "<option value='{$ncsn}' style='padding-left: {$left}px;color:{$color};' $selected>{$nc_title}</option>";
            }
        }

        return $option;
    }

    //判斷目前登入者在哪些類別中有發表的權利
    public function chk_user_cate_power($kind = 'post')
    {
        global $xoopsDB, $xoopsUser;
        if (empty($xoopsUser)) {
            return false;
        }

        $isAdmin = $xoopsUser->isAdmin($this->module_id);
        if ($isAdmin) {
            $ok_cat[] = 0;
        }
        $user_array = $xoopsUser->getGroups();

        $col = ('post' === $kind) ? 'enable_post_group' : 'enable_group';

        //非管理員才要檢查
        $where = ($isAdmin) ? '' : "where $col!=''";

        $sql = "select ncsn,{$col} from " . $xoopsDB->prefix('tad_news_cate') . " $where";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        while (list($ncsn, $power) = $xoopsDB->fetchRow($result)) {
            if ($isAdmin or 'pass' === $kind) {
                $ok_cat[] = (int) $ncsn;
            } else {
                $power_array = explode(',', $power);
                foreach ($power_array as $gid) {
                    // $gid = (int) $gid;
                    if (in_array($gid, $user_array)) {
                        $ok_cat[] = (int) $ncsn;
                        break;
                    }
                }
            }
        }

        return $ok_cat;
    }

    //批次工具
    private function batch_tool()
    {
        //分析目前觀看得是新聞還是自訂頁面
        //$not_news=($this->kind=="news")?'0':'1';

        $move = "<label class='radio'>
        <input type='radio' name='act' value='move_news'>" . _TADNEWS_MOVE_TO . "
        </label>
        <select name='ncsn'>" . $this->get_tad_news_cate_option(0, 0, '', true, '', '1') . '</select>';
        $del = "<label class='radio'><input type='radio' name='act' value='del_news'>" . _TADNEWS_DEL . '</label>';

        $tool = '
        <div class="row">
          <h3>' . _TADNEWS_BATCH_TOOLS . "</h3>
          <div class='well card card-body bg-light m-1'>
            <div class='col-sm-3'>{$move}</div>
            <div class='col-sm-3'>{$del}</div>
            <div class='col-sm-3'>
            <input type='hidden' name='kind' value='{$this->kind}'>
            <input type='hidden' name='op' value='batch'>
            <input type='submit' value='" . _TADNEWS_NP_SUBMIT . "'>
            </div>
          </div>
        </div>";

        return $tool;
    }

    //判斷本文是否該用戶需簽收
    private function have_read_chk($have_read_group = '', $nsn = '', $mode = "")
    {
        global $xoopsDB, $xoopsUser;

        //取得目前使用者的所屬群組
        if ($xoopsUser) {
            $User_Groups = $xoopsUser->getGroups();
            $uid = $xoopsUser->uid();
        } else {
            //未登入者什麼也不秀出來
            return;
        }

        if (!empty($have_read_group)) {
            require_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
            $XoopsFormHiddenToken = new \XoopsFormHiddenToken();
            $XOOPS_TOKEN = $XoopsFormHiddenToken->render();

            $have_read_group_arr = explode(',', $have_read_group);

            foreach ($User_Groups as $gid) {
                // $gid = (int) $gid;

                if (in_array($gid, $have_read_group_arr)) {
                    $time = $this->chk_sign_status($uid, $nsn);
                    if (!empty($time)) {
                        if ('app' === $mode) {
                            $main = sprintf(_TADNEWS_SIGN_OK, $time);
                        } else {
                            $main = "<div class='col-sm-10 offset1 well' style='background-color:#FFFF99;text-align:center;'>" . sprintf(_TADNEWS_SIGN_OK, $time) . '</div>';
                        }
                    } else {
                        if ('app' === $mode) {
                            $main = false;
                        } else {
                            $main = "
                        <form action='index.php' method='post' class='form-horizontal'>
                            <input type='hidden' name='nsn' value='$nsn'>
                            <input type='hidden' name='uid' value='$uid'>
                            $XOOPS_TOKEN
                            <input type='hidden' name='op' value='have_read'>
                            <div style='text-align:center;'>
                            <button type='submit' class='btn btn-primary btn-large'>" . _TADNEWS_I_HAVE_READ . '</button>
                            </div>
                        </form>';
                        }
                    }

                    return $main;
                }
            }
        }

        //不需簽收
    }

    //判斷簽收時間
    private function chk_sign_status($uid = '', $nsn = '')
    {
        global $xoopsDB, $xoopsUser;
        $sql = 'select sign_time from ' . $xoopsDB->prefix('tad_news_sign') . " where uid='$uid' and nsn='$nsn'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($sign_time) = $xoopsDB->fetchRow($result);

        return $sign_time;
    }

    //取得附檔
    private function get_news_files($nsn = '', $mode = '')
    {
        global $xoopsUser, $xoopsDB;

        //取得目前使用者的所屬群組
        if ($xoopsUser) {
            $reader_uid = $xoopsUser->uid();
        } else {
            $reader_uid = '';
        }

        $this->TadUpFiles->set_col('nsn', $nsn);
        $files = $this->TadUpFiles->show_files('upfile', true, $mode, true, false, null, XOOPS_URL . '/modules/tadnews/index.php', null, 0);
        //上傳表單name, 是否縮圖, 顯示模式 (filename、small), 顯示描述, 顯示下載次數, 數量限制, 自訂路徑, 加密, 自動播放時間(0 or 3000)

        $news = $this->get_tad_news($nsn);

        if ('1' == $this->tadnewsConfig['download_after_read'] and !empty($news['have_read_group'])) {
            $time = $this->chk_sign_status($reader_uid, $nsn);
            if (empty($time) and !empty($files)) {
                $files = ('filename' === $mode) ? _TADNEWS_DOWNLOAD_AFTER_READ : "<div class='well'>" . _TADNEWS_DOWNLOAD_AFTER_READ . '</div>';
            }
        }

        return $files;
    }

    //以流水號取得某筆tad_news資料
    public function get_tad_news($nsn = '', $uid_chk = false, $xlanguage = true)
    {
        global $xoopsDB, $xoopsUser;
        if (empty($nsn)) {
            return;
        }

        $sql = 'select * from ' . $xoopsDB->prefix('tad_news') . " where nsn='$nsn'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);

        $news_content = strip_tags($data['news_content']);

        //支援xlanguage
        $news_content = $this->xlang($news_content, $xlanguage);

        //$data['news_description']=xoops_substr($news_content, 0, 300);
        $data['news_description'] = mb_substr($news_content, 0, 300, _CHARSET);

        if ($uid_chk) {
            if (empty($xoopsUser)) {
                redirect_header('index.php', 3, _TADNEWS_NO_ADMIN_POWER . '<br>' . __FILE__ . ':' . __LINE__);
            }

            $isAdmin = $xoopsUser->isAdmin($this->module_id);
            $uid = $xoopsUser->uid();

            if (!$isAdmin and $uid != $data['uid']) {
                redirect_header('index.php', 3, _TADNEWS_NO_ADMIN_POWER . '<br>' . __FILE__ . ':' . __LINE__);
            }
        }

        return $data;
    }

    //前置字串
    public function mk_prefix_tag($tag_sn, $mode = '1')
    {
        global $xoopsUser, $xoopsDB;

        if (empty($tag_sn)) {
            return;
        }

        $and_enable = ('all' === $mode) ? '' : "and enable='1'";

        $sql = 'select font_color,color,tag from ' . $xoopsDB->prefix('tad_news_tags') . " where `tag_sn`='$tag_sn' {$and_enable}";
        $result = $xoopsDB->query($sql);
        list($font_color, $color, $tag) = $xoopsDB->fetchRow($result);

        $prefix_tag = "<a class='badge' style='background-color:$color;font-weight:normal;color:$font_color;text-shadow:none;' href='" . XOOPS_URL . "/modules/tadnews/index.php?tag_sn=$tag_sn'>$tag</a>";

        return $prefix_tag;
    }

    //抓出目前新聞的上下文編號
    private function news_sort($now_nsn = '', $now_ncsn = '', $mode = '')
    {
        global $xoopsDB, $xoopsUser;

        $news = $this->get_tad_news($now_nsn);

        $myts = \MyTextSanitizer::getInstance();
        $nsnsort = [];

        //判斷是否要檢查日期
        if (!empty($this->view_month)) {
            $date_chk = "and a.start_day like '{$this->view_month}%'";
        } elseif ($this->admin_tool) {
            $date_chk = '';
        } elseif ($this->start_day and $this->end_day) {
            $date_chk = "and a.start_day >= '" . $this->start_day . "' and a.start_day <= '" . $this->end_day . " 23:59:59'";
        } elseif ($this->start_day) {
            $date_chk = "and a.start_day >= '" . $this->start_day . "'";
        } elseif ($this->end_day) {
            $date_chk = "and a.start_day <= '" . $this->end_day . " 23:59:59' ";
        } else {
            $date_chk = "and a.start_day < '" . $this->today . "' and (a.end_day > '" . $this->today . "' or a.end_day = '0000-00-00 00:00:00') ";
        }

        $and_enable = (1 == $this->show_enable) ? "and a.enable='1'" : '';

        if ('page' === $this->kind) {
            $sql_back = 'select a.nsn, a.news_title, a.start_day, a.enable_group, a.ncsn, b.enable_group from ' . $xoopsDB->prefix('tad_news') . ' as a left join ' . $xoopsDB->prefix('tad_news_cate') . " as b on a.ncsn=b.ncsn where  a.page_sort < '{$news['page_sort']}'  $and_enable and b.not_news='1' and a.ncsn='{$news['ncsn']}' order by a.page_sort desc limit 0,1";

            $sql_next = 'select a.nsn, a.news_title, a.start_day, a.enable_group, a.ncsn, b.enable_group from ' . $xoopsDB->prefix('tad_news') . ' as a left join ' . $xoopsDB->prefix('tad_news_cate') . " as b on a.ncsn=b.ncsn where a.page_sort > '{$news['page_sort']}'  $and_enable and b.not_news='1' and a.ncsn='{$news['ncsn']}' order by a.page_sort limit 0,1";
        } else {
            $and_cate = ($now_ncsn) ? "and a.ncsn='$now_ncsn'" : '';

            $sql_back = 'select a.nsn, a.news_title, a.start_day, a.enable_group, a.ncsn, b.enable_group from ' . $xoopsDB->prefix('tad_news') . ' as a left join ' . $xoopsDB->prefix('tad_news_cate') . " as b on a.ncsn=b.ncsn where (a.start_day < '{$news['start_day']}' or a.nsn < '{$news['nsn']}') $and_enable and b.not_news='0' $and_cate $date_chk order by a.start_day desc, a.nsn desc limit 0,1";

            $sql_next = 'select a.nsn, a.news_title, a.start_day, a.enable_group, a.ncsn, b.enable_group from ' . $xoopsDB->prefix('tad_news') . ' as a left join ' . $xoopsDB->prefix('tad_news_cate') . " as b on a.ncsn=b.ncsn where (a.start_day > '{$news['start_day']}'  or a.nsn > '{$news['nsn']}') $and_enable and b.not_news='0' $and_cate $date_chk order by a.start_day , a.nsn limit 0,1";
        }

        if ('' == $mode or 'back' === $mode) {
            $result = $xoopsDB->query($sql_back) or Utility::web_error($sql_back);

            list($nsn, $news_title, $start_day, $enable_group, $ncsn, $cate_enable_group) = $xoopsDB->fetchRow($result);

            if ('back' === $mode) {
                $nsnsort['ncsn'] = $ncsn;
                $nsnsort['nsn'] = $nsn;
                $nsnsort['title'] = $myts->htmlSpecialChars($news_title);
                $nsnsort['date'] = mb_substr($start_day, 0, 10);
            } elseif (!$this->read_power_chk($ncsn, $enable_group, $cate_enable_group)) {
                $nsnsort['back'] = $this->news_sort($nsn, $ncsn, 'back');
            } else {
                $nsnsort['back']['ncsn'] = $ncsn;
                $nsnsort['back']['nsn'] = $nsn;
                $nsnsort['back']['title'] = $myts->htmlSpecialChars($news_title);
                $nsnsort['back']['date'] = mb_substr($start_day, 0, 10);
            }
        }

        if ('' == $mode or 'next' === $mode) {
            $result = $xoopsDB->query($sql_next) or Utility::web_error($sql_next);

            list($nsn, $news_title, $start_day, $enable_group, $ncsn, $cate_enable_group) = $xoopsDB->fetchRow($result);

            if ('next' === $mode) {
                $nsnsort['ncsn'] = $ncsn;
                $nsnsort['nsn'] = $nsn;
                $nsnsort['title'] = $myts->htmlSpecialChars($news_title);
                $nsnsort['date'] = mb_substr($start_day, 0, 10);
            } elseif (!$this->read_power_chk($ncsn, $enable_group, $cate_enable_group)) {
                $nsnsort['next'] = $this->news_sort($nsn, $ncsn, 'next');
            } else {
                $nsnsort['next']['ncsn'] = $ncsn;
                $nsnsort['next']['nsn'] = $nsn;
                $nsnsort['next']['title'] = $myts->htmlSpecialChars($news_title);
                $nsnsort['next']['date'] = mb_substr($start_day, 0, 10);
            }
        }

        return $nsnsort;
    }

    //判斷本文及所屬分類是否允許該用戶之所屬群組觀看
    private function read_power_chk($ncsn = '', $enable_group = '', $cate_enable_group = '')
    {
        global $xoopsDB, $xoopsUser;

        //取得目前使用者的所屬群組
        if ($xoopsUser) {
            $User_Groups = $xoopsUser->getGroups();
        } else {
            $User_Groups = [];
        }

        //判斷本文之所屬分類是否允許該用戶之所屬群組觀看
        if (!$this->chk_cate_power($ncsn, $User_Groups, $cate_enable_group, 'read')) {
            return false;
        }

        //判斷本文是否允許該用戶之所屬群組觀看
        if (!$this->chk_news_power($enable_group, $User_Groups)) {
            return false;
        }

        return true;
    }

    //計數器
    public function add_counter($nsn)
    {
        global $xoopsDB;

        $sql = 'update ' . $xoopsDB->prefix('tad_news') . " set  counter = counter + 1 where nsn='$nsn'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return $nsn;
    }

    //解析分類的設定檔
    public function get_setup($setup = '')
    {
        if (empty($setup)) {
            return '';
        }
        $set = explode(';', $setup);
        foreach ($set as $s) {
            $ss = explode('=', $s);
            $key = $ss[0];
            $val = $ss[1];
            $all[$key] = $val;
        }

        return $all;
    }

    /*********************發布************************
     * @param string $nsn
     * @param string $def_ncsn
     * @param string $mode
     * @return array
     */

    //tad_news編輯表單
    public function tad_news_form($nsn = '', $def_ncsn = '', $mode = '')
    {
        global $xoopsDB, $xoopsUser, $isAdmin, $xoopsTpl, $xoopsModuleConfig, $xoTheme;
        $myts = \MyTextSanitizer::getInstance();

        $FormValidator = new FormValidator('#myForm', false);
        $FormValidator->render('topLeft');

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('del_page_tab', "post.php?op=del_page_tab&nsn=$nsn&sort=", 'sort');

        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        //取得目前使用者的所屬群組
        if ($xoopsUser) {
            $User_Groups = $xoopsUser->getGroups();
        } else {
            $User_Groups = [];
        }

        $ncsn_arr = $this->chk_user_cate_power('post');
        if (empty($ncsn_arr)) {
            redirect_header('index.php', 3, _TADNEWS_NO_ADMIN_POWER . '<br>' . __FILE__ . ':' . __LINE__ . implode(';', $ncsn_arr));
        } else {

            // $news_cate_kind_arr = [];
            // foreach ($ncsn_arr as $ncsn) {
            //     $sql = 'select not_news from ' . $xoopsDB->prefix('tad_news_cate') . " where ncsn='{$ncsn}'";
            //     $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            //     while (list($not_news) = $xoopsDB->fetchRow($result)) {
            //         $news_cate_kind_arr[$ncsn] = $not_news;
            //     }
            // }
        }

        //抓取預設值
        if (!empty($nsn)) {
            //先檢查是否為頁籤
            $this->TadDataCenter->set_col('nsn', $nsn);
            $tab_arr = $this->TadDataCenter->getData();

            $DBV = $this->get_tad_news($nsn, false, false);
        } else {
            $DBV = $tab_arr = [];
        }

        //預設值設定

        $nsn = (!isset($DBV['nsn'])) ? $nsn : $DBV['nsn'];
        $ncsn = (!isset($DBV['ncsn'])) ? $def_ncsn : $DBV['ncsn'];
        $cate = $this->get_tad_news_cate($ncsn);
        $news_title = (!isset($DBV['news_title'])) ? '' : $DBV['news_title'];
        $news_content = (!isset($DBV['news_content'])) ? '' : $DBV['news_content'];
        $start_day = (!isset($DBV['start_day']) or '0000-00-00 00:00:00' === $DBV['start_day']) ? date('Y-m-d H:i:s', xoops_getUserTimestamp(time())) : $DBV['start_day'];
        $end_day = (!isset($DBV['end_day']) or '0000-00-00 00:00:00' === $DBV['end_day']) ? null : $DBV['end_day'];
        $enable = (!isset($DBV['enable'])) ? '' : $DBV['enable'];
        $uid = (!isset($DBV['uid'])) ? $xoopsUser->uid() : $DBV['uid'];

        $passwd = (!isset($DBV['passwd'])) ? '' : $DBV['passwd'];
        $enable_group = (!isset($DBV['enable_group'])) ? '' : explode(',', $DBV['enable_group']);
        $have_read_group = (!isset($DBV['have_read_group'])) ? '' : explode(',', $DBV['have_read_group']);

        $prefix_tag = (!isset($DBV['prefix_tag'])) ? '' : $DBV['prefix_tag'];
        $always_top = (!isset($DBV['always_top'])) ? '0' : $DBV['always_top'];
        $always_top_date = (!isset($DBV['always_top_date']) or '0000-00-00 00:00:00' === $DBV['always_top_date']) ? date('Y-m-d H:i:s', time() + 7 * 86400) : $DBV['always_top_date'];

        $always_top_checked = ('1' == $always_top) ? 'checked' : '';

        $SelectGroup_name = new \XoopsFormSelectGroup('', 'enable_group', false, $enable_group, 4, true);
        $SelectGroup_name->addOption('', _TADNEWS_ALL_OK, false);
        $SelectGroup_name->setExtra("class='col-sm-12 form-control'");
        $enable_group = $SelectGroup_name->render();

        $SelectGroup_name2 = new \XoopsFormSelectGroup('', 'have_read_group', false, $have_read_group, 4, true);
        $SelectGroup_name2->addOption('', _TADNEWS_ALL_NO, false);
        $SelectGroup_name2->setExtra("class='col-sm-12 form-control'");
        $have_read_group = $SelectGroup_name2->render();

        //標籤選單
        $prefix_tag_menu = $this->prefix_tag_menu($prefix_tag);

        //類別選單
        $cate_select = $this->get_tad_news_cate_option(0, 0, $ncsn);
        //新聞選單
        $news_cate_select = $this->get_tad_news_cate_option(0, 0, $ncsn, false, null, 0, '0');
        //自訂頁面選單
        $page_cate_select = $this->get_tad_news_cate_option(0, 0, $ncsn, false, null, 0, '1');

        //取得分類數
        $cate_num = $this->get_cate_num();

        $CkEditor = new CkEditor('tadnews', 'news_content', $news_content);
        $CkEditor->setHeight(350);
        $editor = $CkEditor->render();

        if ($tab_arr) {
            foreach ($tab_arr['tab_content'] as $k => $content) {
                $CkEditor = new CkEditor('tadnews', "tab_content[$k]", $content);
                $CkEditor->setHeight(300);
                $tab_arr['tab_editor'][$k] = $CkEditor->render();
                $tab_arr['tab_title'][$k] = $myts->htmlSpecialChars($tab_arr['tab_title'][$k]);
            }
        } else {
            $CkEditor = new CkEditor('tadnews', 'tab_content[1]', $tab_arr['tab_content'][1]);
            $CkEditor->setHeight(300);
            $tab_editor = $CkEditor->render();
        }

        $op = (empty($nsn)) ? 'insert_tad_news' : 'update_tad_news';

        $pic = $pic_css = '';
        if (!empty($nsn)) {
            $pic = $this->get_news_doc_pic($ncsn, 'news_pic', $nsn, 'big', 'db', true, 'demo_cover_pic');

            if (!empty($pic)) {
                $sql = 'select files_sn,description from ' . $xoopsDB->prefix('tadnews_files_center') . " where `col_name`='news_pic' and `col_sn`='{$nsn}' order by sort limit 0,1";
                $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                list($files_sn, $pic_css) = $xoopsDB->fetchRow($result);
            }
        } else {
            $files_sn = '';
            $pic_css = '';
        }
        $use_pic_css = empty($pic_css) ? '' : 'true';

        //creat_cate_group
        $new_cate_input = empty($cate_num) ? _TADNEWS_NAME : '';
        $creat_new_cate = empty($cate_num) ? _TADNEWS_CREAT_FIRST_CATE : _TADNEWS_CREAT_NEWS_CATE;
        $creat_cate_tool = ($this->chk_news_power(implode(',', $this->tadnewsConfig['creat_cate_group']), $User_Groups)) ? 1 : 0;

        if (!empty($this->tadnewsConfig['use_top_group'])) {
            $use_top_tool = ($this->chk_news_power(implode(',', $this->tadnewsConfig['use_top_group']), $User_Groups)) ? 1 : 0;
        } else {
            $use_top_tool = 1;
        }

        $now = time();
        $jquery_path = Utility::get_jquery(true);

        $css = $this->get_pic_css($pic_css);
        $pic_css = empty($use_pic_css) ? '' : $this->mk_pic_css($css);

        $cate_menu = empty($cate_num) ? "<div class='col-sm-2 text-right'>" . _TADNEWS_CREAT_FIRST_CATE . _TAD_FOR . '</div>' : "<select name='ncsn' id='ncsn' class='form-control'>$cate_select</select>";

        $form = [];
        if ('return' === $mode) {
            $form['jquery'] = $jquery_path;
            $form['action'] = $_SERVER['PHP_SELF'];
            $form['nsn'] = $nsn;
            $form['ncsn'] = $ncsn;
            $form['uid'] = $uid;
            $form['op'] = $op;
            $form['cate'] = $cate;
            $form['cate_menu'] = $cate_menu;
            $form['cate_select'] = $cate_select;
            $form['news_cate_select'] = $news_cate_select;
            $form['page_cate_select'] = $page_cate_select;
            $form['creat_cate_tool'] = $creat_cate_tool;
            $form['prefix_tag_menu'] = $prefix_tag_menu;
            $form['news_title'] = $news_title;
            $form['news_content'] = $news_content;
            $form['editor'] = $editor;
            $form['tab_editor'] = $tab_editor;
            $form['jquery_tabs_id'] = "jquery-tabs{$now}";
            $form['start_day'] = $start_day;
            $form['end_day'] = $end_day;
            $form['always_top'] = $always_top;
            $form['always_top_checked'] = $always_top_checked;
            $form['always_top_date'] = $always_top_date;
            $form['enable_group'] = $enable_group;
            $form['have_read_group'] = $have_read_group;
            $form['enable_checked1'] = Utility::chk($enable, '1', '1');
            $form['enable_checked0'] = Utility::chk($enable, '0');
            $form['passwd'] = $passwd;
            $form['pic_css'] = $pic_css;
            $form['use_pic_css'] = $use_pic_css;
            $form['pic_css_width'] = $css['width'];
            $form['pic_css_height'] = $css['height'];
            $form['pic_css_border_width'] = $css['border_width'];
            $form['pic_css_border_style'] = $css['border_style'];
            $form['pic_css_border_color'] = $css['border_color'];
            $form['pic_css_float'] = $css['float'];
            $form['pic_css_margin'] = $css['margin'];
            $form['pic_css_background_repeat'] = $css['background_repeat'];
            $form['pic_css_background_position'] = $css['background_position'];
            $form['pic_css_background_size'] = $css['background_size'];
            $form['pic'] = $pic;
            $form['files_sn'] = $files_sn;
            $form['new_cate_input'] = $new_cate_input;
            $form['creat_new_cate'] = $creat_new_cate;
            $form['use_top_tool'] = $use_top_tool;
            $form['top_max_day'] = $xoopsModuleConfig['top_max_day'];
            // $form['news_cate_kind_arr'] = $news_cate_kind_arr;

            $this->TadUpFiles->set_col('nsn', $nsn);
            $upform = $this->TadUpFiles->upform(true, 'upfile', null, true, null, true, 'upform');
            $form['upform'] = $upform;
            $page_upform = $this->TadUpFiles->upform(true, 'upfile', null, true, null, true, 'page_upform');
            $form['page_upform'] = $page_upform;

            $form['tab_arr'] = $tab_arr;
            require_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
            $XoopsFormHiddenToken = new \XoopsFormHiddenToken();
            $XOOPS_TOKEN = $XoopsFormHiddenToken->render();
            $form['XOOPS_TOKEN'] = $XOOPS_TOKEN;

            return $form;
        }
        $xoopsTpl->assign('jquery', $jquery_path);
        $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
        $xoopsTpl->assign('nsn', $nsn);
        $xoopsTpl->assign('ncsn', $ncsn);
        $xoopsTpl->assign('uid', $uid);
        $xoopsTpl->assign('op', $op);
        $xoopsTpl->assign('cate', $cate);
        $xoopsTpl->assign('cate_menu', $cate_menu);
        $xoopsTpl->assign('cate_select', $cate_select);
        $xoopsTpl->assign('news_cate_select', $news_cate_select);
        $xoopsTpl->assign('page_cate_select', $page_cate_select);
        $xoopsTpl->assign('creat_cate_tool', $creat_cate_tool);
        $xoopsTpl->assign('prefix_tag_menu', $prefix_tag_menu);
        $xoopsTpl->assign('news_title', $news_title);
        $xoopsTpl->assign('news_content', $news_content);
        $xoopsTpl->assign('editor', $editor);
        $xoopsTpl->assign('tab_editor', $tab_editor);
        $xoopsTpl->assign('jquery_tabs_id', "jquery-tabs{$now}");
        $xoopsTpl->assign('start_day', $start_day);
        $xoopsTpl->assign('end_day', $end_day);
        $xoopsTpl->assign('always_top', $always_top);
        $xoopsTpl->assign('always_top_checked', $always_top_checked);
        $xoopsTpl->assign('always_top_date', $always_top_date);
        $xoopsTpl->assign('enable_group', $enable_group);
        $xoopsTpl->assign('have_read_group', $have_read_group);
        $xoopsTpl->assign('enable_checked1', Utility::chk($enable, '1', '1'));
        $xoopsTpl->assign('enable_checked0', Utility::chk($enable, '0'));
        $xoopsTpl->assign('passwd', $passwd);
        $xoopsTpl->assign('pic_css', $pic_css);
        $xoopsTpl->assign('use_pic_css', $use_pic_css);
        $xoopsTpl->assign('pic_css_width', $css['width']);
        $xoopsTpl->assign('pic_css_height', $css['height']);
        $xoopsTpl->assign('pic_css_border_width', $css['border_width']);
        $xoopsTpl->assign('pic_css_border_style', $css['border_style']);
        $xoopsTpl->assign('pic_css_border_color', $css['border_color']);
        $xoopsTpl->assign('pic_css_float', $css['float']);
        $xoopsTpl->assign('pic_css_margin', $css['margin']);
        $xoopsTpl->assign('pic_css_background_repeat', $css['background_repeat']);
        $xoopsTpl->assign('pic_css_background_position', $css['background_position']);
        $xoopsTpl->assign('pic_css_background_size', $css['background_size']);
        $xoopsTpl->assign('pic', $pic);
        $xoopsTpl->assign('files_sn', $files_sn);
        $xoopsTpl->assign('new_cate_input', $new_cate_input);
        $xoopsTpl->assign('creat_new_cate', $creat_new_cate);
        $xoopsTpl->assign('use_top_tool', $use_top_tool);
        $xoopsTpl->assign('top_max_day', $xoopsModuleConfig['top_max_day']);
        // $xoopsTpl->assign('news_cate_kind_arr', $news_cate_kind_arr);

        $this->TadUpFiles->set_col('nsn', $nsn);
        $upform = $this->TadUpFiles->upform(true, 'upfile', null, true, null, true, 'upform');
        $xoopsTpl->assign('upform', $upform);
        $deny_type = $xoopsModuleConfig['upload_deny'] == '' ? '' : sprintf(_MD_TADNEWS_DENY_TYPE, str_replace(';', _MD_TADNEWS_AND, $xoopsModuleConfig['upload_deny']));

        $xoopsTpl->assign('deny_type', $deny_type);
        $page_upform = $this->TadUpFiles->upform(true, 'upfile', null, true, null, true, 'page_upform');
        $xoopsTpl->assign('page_upform', $page_upform);

        $xoopsTpl->assign('tab_arr', $tab_arr);
        require_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
        $XoopsFormHiddenToken = new \XoopsFormHiddenToken();
        $XOOPS_TOKEN = $XoopsFormHiddenToken->render();
        $xoopsTpl->assign('XOOPS_TOKEN', $XOOPS_TOKEN);

        Utility::add_migrate();
    }

    //標籤下拉選單
    private function prefix_tag_menu($prefix_tag = '')
    {
        global $xoopsDB;

        $sql = 'SELECT tag_sn,color,tag FROM ' . $xoopsDB->prefix('tad_news_tags') . " WHERE `enable`='1'";
        $result = $xoopsDB->query($sql);
        $option = '';
        while (list($tag_sn, $color, $tag) = $xoopsDB->fetchRow($result)) {
            $selected = ($prefix_tag == $tag_sn) ? 'selected' : '';
            $option .= "<option value='{$tag_sn}' $selected>{$tag}</option>";
        }

        $select = "<select name='prefix_tag' class='form-control'><option value=''>" . _TADNEWS_PREFIX_TAG . "</option>$option</select>";

        return $select;
    }

    //取得分類數
    private function get_cate_num()
    {
        global $xoopsDB;
        $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_news_cate') . " WHERE not_news='0'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($count) = $xoopsDB->fetchRow($result);

        return $count;
    }

    //取得新聞封面圖片檔案
    public function get_news_doc_pic($ncsn = '', $col_name = '', $col_sn = '', $mode = 'big', $style = 'db', $only_url = false, $id = 'cover_pic')
    {
        global $xoopsDB, $xoopsUser;

        $sql = 'select * from ' . $xoopsDB->prefix('tadnews_files_center') . " where `col_name`='{$col_name}' and `col_sn`='{$col_sn}' order by sort";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            //以下會產生這些變數： $files_sn, $col_name, $col_sn, $sort, $kind, $file_name, $file_type, $file_size, $description
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $style_set = ('db' === $style) ? $description : $style;

            if (empty($style) and !$only_url) {
                return;
            }

            if ('big' !== $mode) {
                if ($only_url) {
                    return XOOPS_URL . "/uploads/tadnews/image/.thumbs/{$file_name}";
                }
                $img = ('db' === $style) ? "<a href='" . XOOPS_URL . "/modules/tadnews/index.php?ncsn={$ncsn}&nsn=$col_sn'><div id='$id' style='background-image:url(" . XOOPS_URL . "/uploads/tadnews/image/.thumbs/{$file_name});{$style_set}'></div></a>" : "<a href='" . XOOPS_URL . "/modules/tadnews/index.php?ncsn={$ncsn}&nsn=$col_sn' class='thumbnails' style='{$style_set}'><img src='" . XOOPS_URL . "/uploads/tadnews/image/.thumbs/{$file_name}' alt='{$file_name}' title='{$file_name}' style='width: 100%;'></a>";

                return $img;
            }
            if ($only_url) {
                return XOOPS_URL . "/uploads/tadnews/image/{$file_name}";
            }
            $img = ('db' === $style) ? "<a href='" . XOOPS_URL . "/modules/tadnews/index.php?ncsn={$ncsn}&nsn=$col_sn'><div id='$id' style='background-image:url(" . XOOPS_URL . "/uploads/tadnews/image/{$file_name});{$style_set}'></div></a>" : "<a href='" . XOOPS_URL . "/modules/tadnews/index.php?ncsn={$ncsn}&nsn=$col_sn' class='thumbnails' style='{$style_set}'><img src='" . XOOPS_URL . "/uploads/tadnews/image/{$file_name}' alt='{$file_name}' title='{$file_name}' style='width: 100%;'></a>";

            return $img;
        }
    }

    //取得圖片的 CSS 設定
    private function get_pic_css($pic_css = '')
    {
        if (empty($pic_css)) {
            $css['width'] = 200;
            $css['height'] = 150;
            $css['border_width'] = '1';
            $css['border_style'] = 'solid';
            $css['border_color'] = '#909090';
            $css['background_position'] = 'center center';
            $css['background_repeat'] = 'no-repeat';
            $css['float'] = 'right';
            $css['margin'] = '4';
            $css['background_size'] = 'cover';
        } else {
            $cssArr = explode(';', $pic_css);
            foreach ($cssArr as $css_set) {
                if (!empty($css_set) and false !== mb_strpos($css_set, ':')) {
                    list($k, $v) = explode(':', $css_set);
                    $k = trim($k);
                    $v = trim($v);
                    $set[$k] = $v;
                }
            }
            $css['width'] = null === $set['width'] ? null : str_replace('px', '', $set['width']);
            $css['height'] = null === $set['height'] ? null : str_replace('px', '', $set['height']);
            if (null !== $set['border']) {
                list($borderwidth, $borderstyle, $bordercolor) = explode(' ', $set['border']);
                $css['border_width'] = null === $borderwidth ? null : str_replace('px', '', $borderwidth);
                $css['border_style'] = null === $borderstyle ? null : $borderstyle;
                $css['border_color'] = null === $bordercolor ? null : $bordercolor;
            }

            $css['background_position'] = null === $set['background-position'] ? null : $set['background-position'];
            $css['background_repeat'] = null === $set['background-repeat'] ? null : $set['background-repeat'];
            $css['float'] = null === $set['float'] ? null : $set['float'];
            $css['margin'] = null === $set['margin'] ? null : str_replace('px', '', $set['margin']);
            $css['background_size'] = null === $set['background-size'] ? null : $set['background-size'];
        }

        return $css;
    }

    //新增資料到tad_news中
    public function insert_tad_news()
    {
        global $xoopsDB, $xoopsUser, $isAdmin, $xoTheme;
        $uid = $xoopsUser->uid();

        //安全判斷
        if (!$GLOBALS['xoopsSecurity']->check()) {
            $error = implode('<br>', $GLOBALS['xoopsSecurity']->getErrors());
            redirect_header('index.php', 3, $error);
        }

        if (empty($_POST['enable_group']) or in_array('', $_POST['enable_group'])) {
            $enable_group = '';
        } else {
            $enable_group = implode(',', $_POST['enable_group']);
        }

        //需簽收群組
        if (empty($_POST['have_read_group']) or in_array('', $_POST['have_read_group'])) {
            $have_read_group = '';
        } else {
            $have_read_group = implode(',', $_POST['have_read_group']);
        }

        $myts = \MyTextSanitizer::getInstance();
        $ncsn = (int) $_POST['ncsn'];
        $tab_mode = (int) $_POST['tab_mode'];
        $not_news = (int) $_POST['not_news'];
        $new_cate = $myts->addSlashes($_POST['new_cate']);
        $new_page_cate = $myts->addSlashes($_POST['new_page_cate']);
        $news_title = $myts->addSlashes($_POST['news_title']);
        $have_read_group = $myts->addSlashes($have_read_group);
        $enable_group = $myts->addSlashes($enable_group);

        //新分類
        if (!empty($new_cate)) {
            $ncsn = $this->creat_tad_news_cate($ncsn, $new_cate);
        } elseif (!empty($new_page_cate)) {
            $ncsn = $this->creat_tad_news_cate($ncsn, $new_page_cate, 1);
        } else {
            $ncsn = (int) $ncsn;
        }

        //若是頁籤模式
        if (1 == $tab_mode) {
            // if ($xoTheme) {
            //     $xoTheme->addStylesheet('modules/tadtools/Easy-Responsive-Tabs/css/easy-responsive-tabs.css');
            //     $xoTheme->addScript("modules/tadtools/Easy-Responsive-Tabs/js/easyResponsiveTabs.js");
            //     $tabs_content = "
            //     <div id='PageTab'>";
            // } else {
            $tabs_content = "
                <link rel='stylesheet' href='" . XOOPS_URL . "/modules/tadtools/Easy-Responsive-Tabs/css/easy-responsive-tabs.css' type='text/css'>
                <script src='" . XOOPS_URL . "/modules/tadtools/Easy-Responsive-Tabs/js/easyResponsiveTabs.js' type='text/javascript'></script>
                <div id='PageTab'>";
            // }

            $tab_title_data_arr = $tab_content_data_arr = [];
            $tab_title_div = $tab_content_div = '';
            foreach ($_POST['tab_title'] as $tab_key => $tab_val) {
                $tab_key = $myts->addSlashes($tab_key);
                $tab_val = $myts->addSlashes($tab_val);

                $tab_data_arr['tab_title'][$tab_key] = $tab_val;
                $tab_title_div .= "<li>$tab_val</li>";

                $tab_content_key = $myts->addSlashes($_POST['tab_content'][$tab_key]);
                $tab_data_arr['tab_content'][$tab_key] = $tab_content_key;

                $tab_content_div .= "
                <div>
                    {$tab_content_key}
                </div>";
            }

            $tabs_content .= "
                <ul class='resp-tabs-list vert'>
                    {$tab_title_div}
                </ul>
                <div class='resp-tabs-container vert'>
                    {$tab_content_div}
                </div>
            </div>
            <script type='text/javascript'>
                $(document).ready(function(){
                  $('#PageTab').easyResponsiveTabs({
                        tabidentify: 'vert',
                        type: 'default', //Types: default, vertical, accordion
                        width: 'auto',
                        fit: true,
                        closed: false
                    });
                });
            </script>
            ";
            $news_content = $myts->addSlashes($tabs_content);
        } else {
            $news_content = $myts->addSlashes($_POST['news_content']);
        }
        $always_top = (empty($_POST['always_top'])) ? '0' : '1';
        $pic_css = empty($_POST['pic_css']['use_pic_css']) ? '' : $this->mk_pic_css($_POST['pic_css']);

        if (!empty($_FILES['upfile2']) and empty($pic_css) and $_POST['pic_css']['use_pic_css']) {
            $pic_css = $this->tadnewsConfig['cover_pic_css'];
        }

        if (empty($_POST['end_day'])) {
            $_POST['end_day'] = '0000-00-00 00:00:00';
        }

        $start_day = $myts->addSlashes($_POST['start_day']);
        $end_day = $myts->addSlashes($_POST['end_day']);
        $passwd = $myts->addSlashes($_POST['passwd']);
        $prefix_tag = $myts->addSlashes($_POST['prefix_tag']);
        $always_top_date = $myts->addSlashes($_POST['always_top_date']);
        $enable = (int) $_POST['enable'];

        $sql = 'insert into ' . $xoopsDB->prefix('tad_news') . " (ncsn,news_title,news_content,start_day,end_day,enable,uid,passwd,enable_group,prefix_tag,always_top,always_top_date,have_read_group) values('{$ncsn}','{$news_title}','{$news_content}','{$start_day}','{$end_day}','{$enable}','{$uid}','{$passwd}','{$enable_group}','{$prefix_tag}','{$always_top}','{$always_top_date}','{$have_read_group}')";

        $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $nsn = $xoopsDB->getInsertId();
        if (1 == $tab_mode) {
            $this->TadDataCenter->set_col('nsn', $nsn);
            $this->TadDataCenter->saveCustomData($tab_data_arr);
        }

        //處理上傳的檔案
        $this->TadUpFiles->set_col('nsn', $nsn);
        $this->TadUpFiles->upload_file('upfile', $this->tadnewsConfig['pic_width'], $this->tadnewsConfig['thumb_width'], null, null, true, false, 'file_name', '', $xoopsModuleConfig['upload_deny']);

        //修改暫存封面圖
        if ($_POST['files_sn']) {
            $pic_css = empty($_POST['pic_css']['use_pic_css']) ? '' : $this->mk_pic_css($_POST['pic_css']);

            $files_sn = (int) $_POST['files_sn'];
            $sql = 'update ' . $xoopsDB->prefix('tadnews_files_center') . " set col_name='news_pic' , col_sn='{$nsn}' , description='{$pic_css}' where files_sn='$files_sn'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            $pic = $this->get_news_doc_pic($ncsn, 'news_pic', $nsn, 'big', 'db', true, 'demo_cover_pic');

            $ff = explode('.', $_FILES['upfile2']['name']);
            foreach ($ff as $ext_name) {
                $ext = mb_strtolower($ext_name);
            }
            $new_name = "news_pic_{$nsn}_1.{$ext}";
            $this->TadUpFiles->rename_file($files_sn, $new_name);
        }

        $xoopsUser->incrementPost();

        $cate = $this->get_tad_news_cate($ncsn);
        $page = ('1' == $cate['not_news']) ? 'page' : 'index';
        header('location: ' . XOOPS_URL . "/modules/tadnews/{$page}.php?ncsn={$ncsn}&nsn={$nsn}");
        exit;

        return $nsn;
    }

    //新增資料到tad_news_cate中
    private function creat_tad_news_cate($of_ncsn = '', $new_cate = '', $not_news = '0')
    {
        global $xoopsDB;
        //安全判斷
        // if (!$GLOBALS['xoopsSecurity']->check()) {
        //     $error = implode("<br>", $GLOBALS['xoopsSecurity']->getErrors());
        //     redirect_header("index.php", 3, $error);
        // }
        $enable_group = $enable_post_group = $setup = $cate = '';
        if (!empty($of_ncsn)) {
            $cate = $this->get_tad_news_cate($of_ncsn);
            $enable_group = $cate['enable_group'];
            $enable_post_group = $cate['enable_post_group'];
        }
        $setup = ('1' == $not_news and !empty($cate['setup'])) ? $cate['setup'] : 'title=1;tool=0;comm=0;nav=1;breadcrumbs=1';
        $sort = $this->get_max_sort($of_ncsn);

        $myts = \MyTextSanitizer::getInstance();
        $new_cate = $myts->addSlashes($new_cate);
        if (empty($of_ncsn)) {
            $of_ncsn = 0;
        }

        $sql = 'insert into ' . $xoopsDB->prefix('tad_news_cate') . " (of_ncsn,nc_title,enable_group,enable_post_group,sort,not_news,setup) values('{$of_ncsn}','{$new_cate}','{$enable_group}','{$enable_post_group}','{$sort}','{$not_news}','{$setup}')";

        $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, _TADNEWS_DB_ADD_ERROR1);
        //取得最後新增資料的流水編號
        $ncsn = $xoopsDB->getInsertId();

        return $ncsn;
    }

    //自動取得新排序
    public function get_max_sort($of_ncsn = '', $not_news = 0)
    {
        global $xoopsDB;
        $sql = 'select max(sort) from ' . $xoopsDB->prefix('tad_news_cate') . " where of_ncsn='$of_ncsn' and not_news='$not_news'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($sort) = $xoopsDB->fetchRow($result);

        return ++$sort;
    }

    //把圖片的 CSS 設定整成成一般css
    private function mk_pic_css($set = '')
    {
        if (empty($set)) {
            $pic_css = '';
        } else {
            $pic_css = '';
            $pic_css .= null === $set['width'] ? '' : "width:{$set['width']}px; ";
            $pic_css .= null === $set['height'] ? '' : "height:{$set['height']}px; ";
            $pic_css .= null === $set['border_width'] ? '' : "border:{$set['border_width']}px {$set['border_style']} {$set['border_color']}; ";
            $pic_css .= null === $set['background_position'] ? '' : "background-position:{$set['background_position']}; ";
            $pic_css .= null === $set['background_repeat'] ? '' : "background-repeat:{$set['background_repeat']}; ";
            $pic_css .= null === $set['background_size'] ? '' : "background-size:{$set['background_size']}; ";
            $pic_css .= null === $set['float'] ? '' : "float:{$set['float']}; ";
            $pic_css .= null === $set['margin'] ? '' : "margin:{$set['margin']}px; ";
        }

        return $pic_css;
    }

    //以流水號取得某筆tad_news_cate資料
    public function get_tad_news_cate($ncsn = '')
    {
        global $xoopsDB;
        if (empty($ncsn)) {
            return;
        }

        $ncsn = (int) $ncsn;
        $sql = 'select * from ' . $xoopsDB->prefix('tad_news_cate') . " where ncsn='$ncsn'";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);

        $sql2 = 'select count(*) from ' . $xoopsDB->prefix('tad_news') . " where ncsn='{$ncsn}'";
        $result2 = $xoopsDB->query($sql2);
        list($counter) = $xoopsDB->fetchRow($result2);
        $data['count'] = $counter;
        $data['g_txt'] = Utility::txt_to_group_name($data['enable_group'], _TADNEWS_ALL_OK);
        $data['gp_txt'] = Utility::txt_to_group_name($data['enable_post_group'], _MD_TADNEWS_ONLY_ROOT, ' , ');

        return $data;
    }

    //更新tad_news某一筆資料
    public function update_tad_news($nsn = '')
    {
        global $xoopsDB, $xoopsUser, $isAdmin, $xoopsModuleConfig;
        $uid = $xoopsUser->uid();

        //確認有管理員或本人才能管理
        $news = $this->get_tad_news($nsn, false);
        // if (!$this->chk_who($news['uid'])) {
        //     redirect_header($_SERVER['PHP_SELF'], 3, _TADNEWS_NO_ADMIN_POWER . '<br>' . __FILE__ . ':' . __LINE__);
        // }

        //可讀群組
        if (empty($_POST['enable_group']) or in_array('', $_POST['enable_group'])) {
            $enable_group = '';
        } else {
            $enable_group = implode(',', $_POST['enable_group']);
        }

        //需簽收群組
        if (empty($_POST['have_read_group']) or in_array('', $_POST['have_read_group'])) {
            $have_read_group = '';
        } else {
            $have_read_group = implode(',', $_POST['have_read_group']);
        }

        $myts = \MyTextSanitizer::getInstance();

        $ncsn = (int) $_POST['ncsn'];
        $new_cate = $myts->addSlashes($_POST['new_cate']);
        $new_page_cate = $myts->addSlashes($_POST['new_page_cate']);
        if (!empty($_POST['new_cate'])) {
            $ncsn = $this->creat_tad_news_cate($ncsn, $new_cate);
        } elseif (!empty($_POST['new_page_cate'])) {
            $ncsn = $this->creat_tad_news_cate($ncsn, $new_page_cate, 1);
        }

        $news_title = $myts->addSlashes($_POST['news_title']);
        //若是頁籤模式
        if (1 == $_POST['tab_mode']) {
            // if ($xoTheme) {
            //     $xoTheme->addStylesheet('modules/tadtools/Easy-Responsive-Tabs/css/easy-responsive-tabs.css');
            //     $xoTheme->addScript("modules/tadtools/Easy-Responsive-Tabs/js/easyResponsiveTabs.js");
            //     $tabs_content = "
            //     <div id='PageTab'>";
            // } else {
            $tabs_content = "
                <link rel='stylesheet' href='" . XOOPS_URL . "/modules/tadtools/Easy-Responsive-Tabs/css/easy-responsive-tabs.css' type='text/css'>
                <script src='" . XOOPS_URL . "/modules/tadtools/Easy-Responsive-Tabs/js/easyResponsiveTabs.js' type='text/javascript'></script>
                <div id='PageTab'>";
            // }

            $tab_title_data_arr = $tab_content_data_arr = [];
            $tab_title_div = $tab_content_div = '';
            foreach ($_POST['tab_title'] as $tab_key => $tab_val) {
                $tab_data_arr['tab_title'][$tab_key] = $tab_val;
                $tab_title_div .= "<li>$tab_val</li>";

                $tab_data_arr['tab_content'][$tab_key] = $_POST['tab_content'][$tab_key];

                $tab_content_div .= "
                <div>
                    {$_POST['tab_content'][$tab_key]}
                </div>";
            }

            $tabs_content .= "
                <ul class='resp-tabs-list vert'>
                    {$tab_title_div}
                </ul>
                <div class='resp-tabs-container vert'>
                    {$tab_content_div}
                </div>
            </div>
            <script type='text/javascript'>
                $(document).ready(function(){
                    $('#PageTab').easyResponsiveTabs({
                        tabidentify: 'vert',
                        type: 'default', //Types: default, vertical, accordion
                        width: 'auto',
                        fit: true,
                        closed: false
                    });
                });
            </script>
            ";
            $news_content = $myts->addSlashes($tabs_content);
        } else {
            $news_content = $myts->addSlashes($_POST['news_content']);
        }

        $always_top = (int) $_POST['always_top'];

        $end_day = empty($_POST['end_day']) ? '0000-00-00 00:00:00' : $myts->addSlashes($_POST['end_day']);

        $sql = 'update ' . $xoopsDB->prefix('tad_news') . " set  ncsn = '{$ncsn}', news_title = '{$news_title}', news_content = '{$news_content}', start_day = '{$_POST['start_day']}', end_day = '{$end_day}', enable = '{$_POST['enable']}', passwd = '{$_POST['passwd']}', enable_group = '{$enable_group}', prefix_tag='{$_POST['prefix_tag']}', always_top='{$always_top}', always_top_date='{$_POST['always_top_date']}', have_read_group='{$have_read_group}' where nsn='$nsn'";

        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        if (1 == $_POST['tab_mode']) {
            $this->TadDataCenter->set_col('nsn', $nsn);
            $this->TadDataCenter->saveCustomData($tab_data_arr);
        }

        //處理上傳的檔案
        $this->TadUpFiles->set_col('nsn', $nsn);
        $this->TadUpFiles->upload_file('upfile', $this->tadnewsConfig['pic_width'], $this->tadnewsConfig['thumb_width'], null, null, true, false, 'file_name', '', $xoopsModuleConfig['upload_deny']);

        //修改暫存封面圖
        if ($_POST['files_sn']) {
            $pic_css = empty($_POST['pic_css']['use_pic_css']) ? '' : $this->mk_pic_css($_POST['pic_css']);

            $files_sn = (int) $_POST['files_sn'];
            $sql = 'update ' . $xoopsDB->prefix('tadnews_files_center') . " set col_name='news_pic' , col_sn='{$nsn}' , description='{$pic_css}' where files_sn='$files_sn'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }

        $cate = $this->get_tad_news_cate($ncsn);
        $page = ('1' == $cate['not_news']) ? 'page' : 'index';
        header('location: ' . XOOPS_URL . "/modules/tadnews/{$page}.php?ncsn={$ncsn}&nsn={$nsn}");
        exit;
    }

    //啟動tad_news某一筆資料
    public function enable_tad_news($nsn = '')
    {
        global $xoopsDB, $xoopsUser;
        $uid = $xoopsUser->uid();

        //確認有管理員或本人才能管理
        $news = $this->get_tad_news($nsn);
        if (!$this->chk_who($news['uid'])) {
            redirect_header($_SERVER['PHP_SELF'], 3, _TADNEWS_NO_ADMIN_POWER . '<br>' . __FILE__ . ':' . __LINE__);
        }

        $sql = 'update ' . $xoopsDB->prefix('tad_news') . " set enable = '1' where nsn='$nsn'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $ncsn = (int) $_POST['ncsn'];
        $cate = $this->get_tad_news_cate($ncsn);
        $page = ('1' == $cate['not_news']) ? 'page' : 'index';
        header('location: ' . XOOPS_URL . "/modules/tadnews/{$page}.php?ncsn={$ncsn}&nsn={$nsn}");
        exit;
    }

    //身份查核
    private function chk_who($author_id = '')
    {
        global $xoopsDB, $xoopsUser;
        if (empty($xoopsUser)) {
            return false;
        }

        $isAdmin = $xoopsUser->isAdmin($this->module_id);
        if ($isAdmin) {
            return true;
        }

        $uid = $xoopsUser->uid();
        if ($uid == $author_id) {
            return true;
        }

        return false;
    }

    //刪除tad_news某筆資料資料
    public function delete_tad_news($nsn = '')
    {
        global $xoopsDB, $xoopsUser;

        //確認有管理員或本人才能管理
        $news = $this->get_tad_news($nsn);
        if (!$this->chk_who($news['uid'])) {
            redirect_header($_SERVER['PHP_SELF'], 3, _TADNEWS_NO_ADMIN_POWER . '<br>' . __FILE__ . ':' . __LINE__);
        }

        $sql = 'delete from ' . $xoopsDB->prefix('tad_news') . " where nsn='$nsn'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //刪除檔案
        //del_files("","nsn",$nsn);

        $this->TadUpFiles->set_col('nsn', $nsn);
        $this->TadUpFiles->del_files();
        $this->delete_cover($nsn);
    }

    //支援xlanguage
    private function xlang($news_content = '', $xlanguage = true)
    {
        if ($xlanguage and function_exists('xlanguage_ml')) {
            $news_content = xlanguage_ml($news_content);
        }

        return $news_content;
    }

    public function delete_cover($nsn = '')
    {
        $this->TadUpFiles->set_col('news_pic', $nsn);
        $this->TadUpFiles->del_files();
    }

    // 刪除頁籤
    public function del_page_tab($nsn, $sort)
    {
        $this->TadDataCenter->set_col('nsn', $nsn);
        $this->TadDataCenter->delData('tab_title', $sort, __FILE__, __LINE__);
        $this->TadDataCenter->delData('tab_content', $sort, __FILE__, __LINE__);
    }

    // 檢查內容是不是只有一行網址
    public function only_url($news_content, $nsn)
    {
        $url = strip_tags($news_content);
        $url = trim($url);
        if (false !== filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }
    }
}
