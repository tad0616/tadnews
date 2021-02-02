<?php
use Xmf\Request;
use XoopsModules\Tadnews\Tadnews;

//條列式新聞區塊
require_once __DIR__ . '/header.php';
xoops_loadLanguage('blocks', 'tadnews');

$num = Request::getInt('num', 10);
$show_ncsn = Request::getString('show_ncsn');
$ncsn_arr = explode(',', $show_ncsn);
$summary_length = Request::getInt('summary_length');
$summary_css = Request::getString('summary_css');
$start_from = Request::getInt('start_from');
$title_length = Request::getInt('title_length');
$show_cover = Request::getString('show_cover');
$cover_css = Request::getString('cover_css');
$display_mode = Request::getString('display_mode');
$show_button = Request::getInt('show_button');
$p = Request::getInt('p');
$randStr = Request::getString('randStr');
$ncsn = Request::getInt('ncsn');
$tag_sn = Request::getInt('tag_sn');
$keyword = Request::getString('keyword');
$start_day = Request::getString('start_day');
$end_day = Request::getString('end_day');

$b = $p - 1;
$n = $p + 1;
$start = $p * $num + $start_from;

if ($start <= 0) {
    $start = 0;
}

$Tadnews->set_show_num($num);
if ($ncsn) {
    $Tadnews->set_view_ncsn($ncsn);
} else {
    $Tadnews->set_view_ncsn($ncsn_arr);
}

if ($tag_sn) {
    $Tadnews->set_view_tag($tag_sn);
}

if ($keyword) {
    $Tadnews->set_keyword($keyword);
}

if ($start_day) {
    $Tadnews->set_start_day($start_day);
}

if ($end_day) {
    $Tadnews->set_end_day($end_day);
}
$Tadnews->set_show_mode('list');
$Tadnews->set_news_kind('news');
$Tadnews->set_summary($summary_length, $summary_css);
$Tadnews->set_title_length($title_length);
$Tadnews->set_cover($show_cover, $cover_css);
$Tadnews->set_skip_news($start);

$block = '';

$total = 0;

//die('display_mode:' . $display_mode);
if ('table' === $display_mode) {
    $block .= "
    <table class='table table-striped table-hover table-shadow'>
        <tbody>";
    $all_news = $Tadnews->get_news('return');

    if (empty($all_news['page'])) {
        die('<tr><td>' . _TADNEWS_EMPTY . '</td></tr>');
    }
    foreach ($all_news['page'] as $news) {
        $need_sign = $news['need_sign'] ? "<img src='{$news['need_sign']}' align='absmiddle' alt='{$news['news_title']}' style='margin:3px;'>" : '';

        $block .= "
        <tr>
            <td>
                {$news['chkbox']}
                {$news['post_date']}
                {$news['prefix_tag']}
                {$need_sign}
                {$news['enable_txt']}{$news['today_pic']}
                <a href='" . XOOPS_URL . "/modules/tadnews/{$news['link_page']}?nsn={$news['nsn']}'>{$news['news_title']}</a>

                <span style='color:gray;font-size: 0.8rem;'> (<a href='" . XOOPS_URL . "/modules/tadnews/index.php?show_uid={$news['uid']}'>{$news['uid_name']}</a> / {$news['counter']} / <a href='" . XOOPS_URL . "/modules/tadnews/{$news['link_page']}?ncsn={$news['ncsn']}'>{$news['cate_name']}</a>)</span> {$news['content']}
            </td>
        </tr>";
        $total++;
    }

    $block .= '
        </tbody>
    </table>';
} else {
    $block .= "<ul>";
    $all_news = $Tadnews->get_news('return');
    if (empty($all_news['page'])) {
        die('<li>' . _TADNEWS_EMPTY . '</li>');
    }
    foreach ($all_news['page'] as $news) {
        $need_sign = $news['need_sign'] ? "<img src='{$news['need_sign']}' align='absmiddle' alt='{$news['news_title']}'>" : '';
        $block .= "
        <li>
            {$news['post_date']}
            {$news['pic']}
            {$news['prefix_tag']}
            {$need_sign}
            {$news['enable_txt']}
            {$news['today_pic']}
            <a href='" . XOOPS_URL . "/modules/tadnews/{$news['link_page']}?nsn={$news['nsn']}'>{$news['news_title']}</a>
            {$news['content']}
        </li>";
        $total++;
    }
    $block .= '
    </ul>';
}

$b_button = ($b < 0) ? '' : "<button onClick='tadnew_list_content{$randStr}({$b})'  onfocus='tadnew_list_content{$randStr}({$b})' class='btn btn-info'>" . sprintf(_TADNEWS_BLOCK_BACK, $num) . '</button>';

$n_button = ($total < $num) ? '' : "<button onClick='tadnew_list_content{$randStr}({$n})' onfocus='tadnew_list_content{$randStr}({$n})' class='btn btn-info'>" . sprintf(_TADNEWS_BLOCK_NEXT, $num) . '</button>';

$m_button = ($total < $num) ? '' : "<a href='" . XOOPS_URL . "/modules/tadnews/' class='btn btn-info'>more</a>";
$button = ($show_button) ? "<div class='row'><div class='col-md-4 text-left'>{$b_button}</div><div class='col-md-4 text-center'>{$m_button}</div><div class='col-md-4 text-right'>{$n_button}</div></div>" : '';

$block .= "
{$button}";

echo $block;
