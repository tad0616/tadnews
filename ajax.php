<?php
include_once "header.php";

include_once XOOPS_ROOT_PATH."/modules/tadnews/class/tadnews.php";
include_once(XOOPS_ROOT_PATH."/modules/tadnews/language/{$xoopsConfig['language']}/blocks.php");
$op=isset($_REQUEST['op'])?$_REQUEST['op']:"";

$num=!empty($_POST['num'])?intval($_POST['num']):10;
$show_button=!empty($_POST['show_button'])?$_POST['show_button']:"";
$start_from=intval($_POST['start_from']);
$show_ncsn=!empty($_POST['show_ncsn'])?$_POST['show_ncsn']:"";
$ncsn_arr=explode(',',$show_ncsn);

$p=!empty($_REQUEST['p'])?intval($_REQUEST['p']):0;
$b=$p-1;
$n=$p+1;
$start=$p*$num+$start_from;

if($start <= 0 )$start=0;
//echo "<p>strat:{$start},p:{$p},b:{$b},n:{$n},start_from:{$start_from},num:{$num}</p>";


$tadnews->set_show_num($num);
$tadnews->set_view_ncsn($ncsn_arr);
$tadnews->set_show_mode('list');
$tadnews->set_news_kind("news");
$tadnews->set_use_star_rating(false);
$tadnews->set_cover(false);
$tadnews->set_skip_news($start);
$all_news=$tadnews->get_news('return');

if(empty($all_news['page']))die(_TADNEWS_EMPTY);

$show_col='';



foreach($_POST['cell'] as $col){
  if($col=='' or $col=='hide')continue;
  $show_col[]=$col;
}

if(empty($show_col))$show_col=array('start_day','news_title','uid','ncsn','counter');


$block="";


$tt['start_day']="<th style='width:80px;'>".to_utf8(_MD_TADNEWS_START_DATE)."</th>";
$tt['news_title']="<th>".to_utf8(_MD_TADNEWS_NEWS_TITLE)."</th>";
$tt['uid']="<th style='width:80px;'>".to_utf8(_MD_TADNEWS_POSTER)."</th>";
$tt['ncsn']="<th style='width:80px;'>".to_utf8(_MD_TADNEWS_NEWS_CATE)."</th>";
$tt['counter']="<th>".to_utf8(_MD_TADNEWS_COUNTER)."</th>";
$blockTitle="";
foreach($show_col as $colname){
  $blockTitle.=$tt[$colname];
}


$block.="<table class='table table-striped'><tr>{$blockTitle}</tr>";


$i=2;

$total=0;
foreach($all_news['page'] as $news){

  $need_sign=(!empty($news['need_sign']))?"<img src='{$news['need_sign']}' align='absmiddle' hspace='3' alt='{$news['news_title']}'>":"";

  $start_day="<td nowrap>{$news['post_date']}</td>";
  $news_title="<td>{$news['prefix_tag']}{$need_sign}{$news['today_pic']} <a href='".XOOPS_URL."/modules/tadnews/index.php?nsn={$news['nsn']}'>{$news['news_title']}</a>{$news['files']}</td>";

  $uid="<td nowrap style='text-align:center;'><a href='".XOOPS_URL."/userinfo.php?uid={$news['uid']}'>{$news['uid_name']}</a></td>";
  $ncsn="<td nowrap style='text-align:center;'><a href='".XOOPS_URL."/modules/tadnews/index.php?ncsn={$news['ncsn']}'>{$news['cate_name']}</a></td>";
  $counter="<td nowrap>{$news['counter']}</td>";
  $news_title=to_utf8($news_title);
  $uid=to_utf8($uid);
  $ncsn=to_utf8($ncsn);
  $block.="<tr>";
  foreach($show_col as $colname){
    $block.=$$colname;
  }

  $block.="</tr>";
  $i++;
  $total++;
}


$b_button=($b < 0)?"":"<button onClick='view_content{$_POST['randStr']}({$b})' class='btn'>".sprintf(_TADNEWS_BLOCK_BACK,$num)."</button>";

$n_button=($total < $num)?"":"<button style='float:right;' onClick='view_content{$_POST['randStr']}({$n})' class='btn'>".sprintf(_TADNEWS_BLOCK_NEXT,$num)."</button>";

$button=($show_button)?"{$n_button}{$b_button}":"";
//$button="{$n_button}{$b_button}";
$block.="</table>
$button
<div style='clear:both;'></div>
";


echo $block;


?>
