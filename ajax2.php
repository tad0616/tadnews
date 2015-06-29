<?php
include_once "header.php";

include_once XOOPS_ROOT_PATH . "/modules/tadnews/class/tadnews.php";
include_once XOOPS_ROOT_PATH . "/modules/tadnews/language/{$xoopsConfig['language']}/blocks.php";

$row          = ($_SESSION['bootstrap'] == '3') ? 'row' : 'row-fluid';
$span         = ($_SESSION['bootstrap'] == '3') ? 'col-md-' : 'span';
$controls_row = ($_SESSION['bootstrap'] == '3') ? 'form-group' : 'control-group';

$num            = !empty($_POST['num']) ? intval($_POST['num']) : 10;
$summary_length = intval($_POST['summary_length']);
$summary_css    = $_POST['summary_css'];
$title_length   = intval($_POST['title_length']);
$show_cover     = $_POST['show_cover'];
$cover_css      = $_POST['cover_css'];
$start_from     = intval($_POST['start_from']);
$show_ncsn      = !empty($_POST['show_ncsn']) ? $_POST['show_ncsn'] : "";
$ncsn_arr       = explode(',', $show_ncsn);

$p     = !empty($_REQUEST['p']) ? intval($_REQUEST['p']) : 0;
$b     = $p - 1;
$n     = $p + 1;
$start = $p * $num + $start_from;

if ($start <= 0) {
    $start = 0;
}

//echo "<p>strat:{$start},p:{$p},b:{$b},n:{$n},start_from:{$start_from},num:{$num}</p>";

$tadnews->set_show_num($num);
$tadnews->set_view_ncsn($ncsn_arr);
$tadnews->set_show_mode('list');
$tadnews->set_news_kind("news");
$tadnews->set_summary($summary_length, $summary_css);
$tadnews->set_title_length($title_length);
$tadnews->set_cover($show_cover, $cover_css);
$tadnews->set_skip_news($start);

//die(var_export($_POST));

$block = "<div class='$row'>";

$total = 0;

$show_button  = !empty($_POST['show_button']) ? $_POST['show_button'] : "0";
$display_mode = $_POST['display_mode'];

//die('display_mode:' . $display_mode);
if ($display_mode == 'table') {
    $block .= "
    <table class='table table-striped'>
      <tbody>";
    $all_news = $tadnews->get_news('return');
    if (empty($all_news['page'])) {
        die(_TADNEWS_EMPTY);
    }
    foreach ($all_news['page'] as $news) {
        $need_sign = $news['need_sign'] ? "<img src='{$news['need_sign']}' align='absmiddle' alt='{$news['news_title']}' style='margin:3px;'>" : "";

        $block .= "
        <tr>
          <td>
            {$news['chkbox']}
            {$news['post_date']}
            {$news['prefix_tag']}
            {$need_sign}
            {$news['enable_txt']}{$news['today_pic']}
            <a href='" . XOOPS_URL . "/modules/tadnews/{$news['link_page']}?nsn={$news['nsn']}'>{$news['news_title']}</a>
            <span style='color:gray;font-size:12px;'> (<a href='" . XOOPS_URL . "/modules/tadnews/index.php?show_uid={$news['uid']}'>{$news['uid_name']}</a> / {$news['counter']} / <a href='" . XOOPS_URL . "/modules/tadnews/{$news['link_page']}?ncsn={$news['ncsn']}'>{$news['cate_name']}</a>)</span> {$news['content']}
          </td>
        </tr>";
        $total++;
    }

    $block .= "
        </tbody>
      </table>";
} else {
    $block .= "<ul>";
    $all_news = $tadnews->get_news('return');
    if (empty($all_news['page'])) {
        die(_TADNEWS_EMPTY);
    }
    foreach ($all_news['page'] as $news) {
        $need_sign = $news['need_sign'] ? "<img src='{$news['need_sign']}' align='absmiddle' alt='{$news['news_title']}' style='margin:3px;'>" : "";
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
    $block .= "
  </ul>";
}

$b_button = ($b < 0) ? "" : "<button onClick='tadnew_list_content{$_POST['randStr']}({$b})'  onfocus='tadnew_list_content{$_POST['randStr']}({$b})' class='btn btn-default'>" . sprintf(_TADNEWS_BLOCK_BACK, $num) . "</button>";

$n_button = ($total < $num) ? "" : "<button onClick='tadnew_list_content{$_POST['randStr']}({$n})' onfocus='tadnew_list_content{$_POST['randStr']}({$n})' class='btn btn-default'>" . sprintf(_TADNEWS_BLOCK_NEXT, $num) . "</button>";

$m_button = ($total < $num) ? "" : "<a href='" . XOOPS_URL . "/modules/tadnews/' class='btn btn-default'>more</a>";
$button   = ($show_button) ? "<div class='$row'><div class='{$span}4 text-left'>{$b_button}</div><div class='{$span}4 text-center'>{$m_button}</div><div class='{$span}4 text-right'>{$n_button}</div></div>" : "";

$block .= "</div>
{$button}";

echo $block;
