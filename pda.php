<?php
/*-----------引入檔案區--------------*/
if(file_exists("mainfile.php")){
  include_once "mainfile.php";
}elseif("../../mainfile.php"){
  include_once "../../mainfile.php";
}
//include_once XOOPS_ROOT_PATH."/modules/tadnews/up_file.php";

include_once XOOPS_ROOT_PATH."/modules/tadnews/language/{$xoopsConfig['language']}/main.php";
include_once XOOPS_ROOT_PATH."/modules/tadnews/function.php";

/*-----------function區--------------*/

function list_tadnews($the_ncsn='',$p){
  global $xoopsDB,$xoopsUser,$xoopsOption,$xoopsTpl,$isAdmin,$xoopsModuleConfig,$tadnews;

  $num=10;
  $p=!empty($_REQUEST['p'])?intval($_REQUEST['p']):0;
  $b=$p-1;
  $n=$p+1;
  $start=$p*$num;

  if($start <= 0 )$start=0;

    $tadnews->set_show_num($num);
    $tadnews->set_skip_news($start);
    $tadnews->set_news_kind("news");
    $tadnews->set_summary("page_break");
    if($the_ncsn>0){
      $tadnews->set_view_ncsn($the_ncsn);
      $tadnews->set_show_mode($xoopsModuleConfig['cate_show_mode']);
    }else{
      $tadnews->set_show_mode($xoopsModuleConfig['show_mode']);
    }
    $tadnews->set_title_length(20);
    $tadnews->set_cover(true,"db");

    //if($xoopsModuleConfig['use_star_rating']=='1'){
    //  $tadnews->set_use_star_rating(true);
    //}

    $tnews=$tadnews->get_news('return');

    $i=2;

    foreach($tnews['page'] as $news){
      //$pic=get_news_doc_pic_m("news_pic",$news['nsn'],"thumb","height:80px;width:80px");

      $all_news.=(empty($the_ncsn))?"
      <li><a href='{$_SERVER['PHP_SELF']}?nsn={$news['nsn']}'>
      <h3 style='white-space:normal;'>{$news['news_title']}</h3>
      <p>{$news['post_date']} | {$news['cate_name']}</p>
      <span class='ui-li-count'>{$news['counter']}</span>
      </a></li>":"
      <li><a href='{$_SERVER['PHP_SELF']}?nsn={$news['nsn']}&ncsn={$news['ncsn']}'>
      <h3 style='white-space:normal;'>{$news['news_title']}</h3>
      <p>{$news['post_date']} | {$news['cate_name']}</p>
      <span class='ui-li-count'>{$news['counter']}</span>
      </a></li>";
      $i++;
      $total++;
    }

  $b_button=($b < 0)?"<a href='#' data-role='button' data-inline='true' data-icon='arrow-l' data-iconpos='left' class='back ui-disabled'>".sprintf(_TADNEWS_BLOCK_BACK,$num)."</a>":"<a href='{$_SERVER['PHP_SELF']}?ncsn={$the_ncsn}&p={$b}' data-role='button' data-inline='true' data-icon='arrow-l' data-iconpos='left' class='back'>".sprintf(_TADNEWS_BLOCK_BACK,$num)."</a>";

  $n_button=($total < $num)?"<a href='#' data-role='button' data-inline='true' data-icon='arrow-r' data-iconpos='right' class='next ui-disabled'>".sprintf(_TADNEWS_BLOCK_NEXT,$num)."</a>":"<a href='{$_SERVER['PHP_SELF']}?ncsn={$the_ncsn}&p={$n}' data-role='button' data-inline='true' data-icon='arrow-r' data-iconpos='right' class='next'>".sprintf(_TADNEWS_BLOCK_NEXT,$num)."</a>";

  $button="{$b_button}{$n_button}";

  $all_news="

  <ul data-role='listview' data-filter='true' data-filter-placeholder='Headline search' class='listview'>$all_news</ul><br>
  <div style='clear:both;text-align:center;padding:8px;' class='navigation'>$button</div>
  ";

  return $all_news;
}

//取得新聞封面圖片檔案
function get_news_doc_pic_m($col_name="",$col_sn="",$mode="big",$style="db",$only_url=false){
  global $xoopsDB,$xoopsUser,$xoopsModule;

  $sql = "select * from ".$xoopsDB->prefix("tadnews_files_center")." where `col_name`='{$col_name}' and `col_sn`='{$col_sn}' order by sort";

  $result=$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));
  while($all=$xoopsDB->fetchArray($result)){
    //以下會產生這些變數： $files_sn, $col_name, $col_sn, $sort, $kind, $file_name, $file_type, $file_size, $description
    foreach($all as $k=>$v){
      $$k=$v;
    }

    $style=($style=='db')?$description:$style;
    if(empty($style) and !$only_url)return;

    if($mode!="big"){
      if($only_url){
        return XOOPS_URL."/uploads/tadnews/image/.thumbs/{$file_name}";
      }else{
        return "<img style='{$style}' src='".XOOPS_URL."/uploads/tadnews/image/.thumbs/{$file_name}'>";
      }
    }else{
      if($only_url){
        return XOOPS_URL."/uploads/tadnews/image/{$file_name}";
      }else{
        return "<img style='{$style}' src='".XOOPS_URL."/uploads/tadnews/image/{$file_name}'>";
      }
    }
  }
  return ;
}

//顯示單一新聞
function show_news($nsn="",$m_ncsn=""){
  global $xoopsUser,$xoopsOption,$xoopsTpl,$isAdmin,$xoopsModuleConfig,$tadnews;

  $tadnews->set_view_nsn($nsn);
  $tadnews->set_cover(true,"db");
  $tadnews->set_summary('full');
  //if($xoopsModuleConfig['use_star_rating']=='1'){
  //  $tadnews->set_use_star_rating(true);
  //}
  $news=$tadnews->get_news('return');

  $back_news="";
  if(!empty($news['page'][0]['back_news_link'])){
    $back_news_link=str_replace("index.php","pda.php",$news['page'][0]['back_news_link']);
    $back_news="<a href='{$back_news_link}' class='nav' data-icon='arrow-u'>{$news['page'][0]['back_news_title']}</a>";
  }

  $next_news="";
  if(!empty($news['page'][0]['next_news_link'])){
    $next_news_link=str_replace("index.php","pda.php",$news['page'][0]['next_news_link']);
    $next_news="<a href='{$next_news_link}' class='nav' data-icon='arrow-d'>{$news['page'][0]['next_news_title']}</a>";
  }



  $style="
  <style>
  #clean_news {
    border: 1px solid #CFCFCF;
    margin: 0px;
    background-image: none;
    padding:0px;
    border-radius: 5px;
    /*font-size:3em;*/
  }
  .nav{
    /*font-size:3em;*/
  }
  #news_head{
    margin:0px;
    padding:8px 8px 0px;
    background-color: #FCFCFC;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
  }
  #clean_news a{
    font-weight:normal;
    border:none;
  }
  #news_title{
    margin: 0px 0px 10px 0px;
    width: 100%;
    background-color: transparent;
  }
  #news_title a{
    font-weight: bolder;
    border: none;
    color:rgb(153,0,0);
  }
  #news_toolbar{
    border:none;
    margin:2px 4px 6px 10px;
  }
  #news_info{
    border:none;
    margin:2px 4px 6px 10px;
    color:gray;
    font-size: 80%;
  }
  #news_content {
    font-weight: normal;
    line-height: 1.5em;
    margin: 10px auto;
    overflow: hidden;
    width: 98%;
    word-wrap: break-word;
  }
  #news_content table{
    table-layout: fixed;
    width: 100%;
    word-wrap: break-word;
    border-collapse:collapse;
  }
  #news_content p {
    margin-top:10px;
    margin-bottom:10px;
  }
  #news_content h1{
    font-size: 140%;
  }
  #news_content h2{
    font-size: 120%;
  }
  #news_content h3{
    font-size: 100%;
  }
  #news_content img {
    max-width: 100%;
    height:auto;
    overflow: hidden;
  }
  .t1 {
    border-top-width: 1px;
    border-left-width: 1px;
    border-top-style: solid;
    border-left-style: solid;
    border-top-color: #666;
    border-left-color: #666;
  }
  .t1 td {
    border-right-width: 1px;
    border-bottom-width: 1px;
    border-right-style: solid;
    border-bottom-style: solid;
    border-right-color: #666;
    border-bottom-color: #666;
    padding: 2px;
  }
  .ui-navbar li:last-child .ui-btn, .ui-navbar .ui-grid-duo .ui-block-b .ui-btn {
    border-right-width: 1px;
    margin-right: 0;
  }
  .ui-navbar li .ui-btn:last-child {
    border-right-width: 1px;
    margin-right: 0;
  }
  .fb-comments, .fb-comments iframe[style], .fb-comments span {
    width: 100% !important;
  }
  .news_img {
    float: right;
    margin: 5px;
  }
  .tadnews_text_shadow {
    text-shadow: 0px 1px 1px #333333;
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    border-radius: 2px;
  }
  </style>
";


  $all=pda_news_format($nsn , $news['page'][0]['news_title'] , $news['page'][0]['content'],$news['page'][0]['fun'] , $news['page'][0]['fun'] ,$news['page'][0]['prefix_tag'] , $news['page'][0]['uid'] ,  $news['page'][0]['ncsn'] , $news['page'][0]['cate_name'] , $news['page'][0]['post_date'], $news['page'][0]['files'] ,$news['page'][0]['g_txt'] , $news['page'][0]['have_read_chk'] , $news['page'][0]['counter']);

  $home="<a href='{$_SERVER['PHP_SELF']}' class='nav'>&#x21E7;"._MD_TADNEWS_TO_MOD."</a>";

  $nav="<div data-role='navbar' data-iconpos='left' style='margin-top:10px;margin-bottom:20px'>
  <ul>
   <li>$back_news</li>
   <li>$next_news</li>
   </ul>
  </div>";

  $facebook_comments=facebook_comments($xoopsModuleConfig['facebook_comments_width'],'tadnews','index.php','nsn',$nsn);

  $main=$style.$syntaxhighlighter_code.$all.$nav.$facebook_comments;

  $tadnews->add_counter($nsn);

  return $main;
}



//新聞頁面
function pda_news_format($nsn="",$title="",$news_content="",$fun="",$fun2="",$prefix_tag="",$uid="",$ncsn="",$cate="",$start_day="",$files="",$have_read_group="",$have_read_chk="",$counter=""){
//die($news_content);
  $uid_name=XoopsUser::getUnameFromId($uid,1);
  $uid_name=(empty($uid_name))?XoopsUser::getUnameFromId($uid,0):$uid_name;

  $sign_bg=(!empty($have_read_group))?"style='background-image:url(".XOOPS_URL."/modules/tadnews/images/sign_bg.png);background-position: right top;background-repeat: no-repeat;'":"";


  $push="<div style='background-color:#E4E4E4;text-align:center;padding:5px'>
  <a title='add to Twitter' href='http://twitter.com/home/?status=http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}' target='_blank'><img src='images/social/social_twitter.png' alt='add to Twitter' width='32' height='32' title='add to Twitter' align='absmiddle'></a>
  <a title='add to Plurk' href='http://www.plurk.com/?qualifier=shares&amp;status=http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}' target='_blank'><img src='images/social/social_plurk.png' alt='add to Plurk' width='32' height='32' title='add to Plurk' align='absmiddle'></a>
  <a title='add to FaceBook' href='http://www.facebook.com/share.php?u=http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}' target='_blank'><img src='images/social/social_facebook.png' alt='add to FaceBook' width='32' height='32' title='add to FaceBook' align='absmiddle'></a>
  <a title='add to Google Plus' href='https://plus.google.com/share?url=http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}' target='_blank'><img src='images/social/social_google.png' alt='add to Google Plus' width='32' height='32' title='add to Google Plus' align='absmiddle'></a>
  <a href='mailto:?subject={$title}&body=http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}' target='_blank'><img src='images/social/social_email.png' alt='email to Friends' width='32' height='32' align='absmiddle'></a>
  </div>
  <div style='clear:both;'></div>";

  $pic=get_news_doc_pic_m("news_pic",$nsn,"big","height:120px;width:120px");

  $post_date=substr($start_day,0,10);

  $main="
  <script type='text/javascript'>
  $('div[data-role=\"page\"]').live('pagebeforeshow',function(){
  $('#page_{$nsn} #news_content table').addClass('t1');
  $('#page_{$nsn} #news_content table tr').filter(':even').css('background-color','rgb(230,230,230)');
  $('#page_{$nsn} #news_content [href],#page_{$nsn} #news_toolbar [href],#page_{$nsn} .thumbnails [href]').attr('rel','external');
  });
  $('#page_{$nsn}').bind('pageshow', function(){
      try{
          FB.XFBML.parse();
      }catch(ex){}
  });
  </script>
  <div id='clean_news'>
    <div id='news_head' $sign_bg>
       <div id='news_title'>{$prefix_tag} <a href='{$_SERVER['PHP_SELF']}?nsn={$nsn}'>{$title}</a></div>
       <div id='news_info'>
       {$uid_name} - {$cate} | {$post_date} | "._TADNEWS_HOT."{$counter}
       </div>
    </div>

    <div id='news_content'><div class='news_img'>{$pic}</div>{$news_content}</div>
    $have_read_chk
    $files
    <div style='clear:both;height:10px;'></div>
    <div id='news_toolbar'>{$fun2}</div>
  $push
  </div>
  <div style='clear:both;'></div>";
  return $main;
}


//取得分類下拉選單
/*function get_tad_news_cate_option_m($v=""){
  global $xoopsDB;

  //$option="<option>"._MD_TADNEWS_NEWS_CATE."</option>";
  $option="<option value='0'>"._MD_TADNEWS_ALL_CATE."</option>";
  //$option=="";
  $sql = "select ncsn,nc_title,not_news from ".$xoopsDB->prefix("tad_news_cate")." where not_news!='1' order by sort";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));

  while(list($ncsn,$nc_title,$not_news)=$xoopsDB->fetchRow($result)){

    $selected=($v==$ncsn)?"selected":"";
    $option.="<option value='{$ncsn}' $selected>{$nc_title}</option>";
  }
  return $option;
}*/

//取得分類下拉選單2
function get_tad_news_cate_list_m(){
  global $xoopsDB;

  $list="<ul data-role='listview' data-theme='a' data-divider-theme='a' style='margin-top:-16px;'>
    <li data-icon='delete' style='background-color:#111;'>
      <a href='#' data-rel='close'>Category</a>
    </li>";

  $sql = "select ncsn,nc_title,not_news from ".$xoopsDB->prefix("tad_news_cate")." where not_news!='1' order by sort";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));

  while(list($ncsn,$nc_title,$not_news)=$xoopsDB->fetchRow($result)){

    $list.="<li><a href='{$_SERVER['PHP_SELF']}?ncsn={$ncsn}'>{$nc_title}</a></li>";
  }
  $list.="</ul>";
  return $list;
}


//列出月份
function month_list_m($now_date=""){
  global $xoopsDB;

  $sql = "select left(start_day,7) , count(*) from ".$xoopsDB->prefix("tad_news")." where enable='1' group by left(start_day,7) order by start_day desc";

  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));

  while(list($ym,$count)=$xoopsDB->fetchRow($result)){
    $opt.="<li><a href='{$_SERVER['PHP_SELF']}?op=archive&date={$ym}'>".str_replace("-",""._MD_TADNEWS_YEAR,$ym)._MD_TADNEWS_MONTH."<span class='ui-li-count'>{$count}</span></a></li>";
  }

  $opt="<ul data-role='listview'>$opt</ul>";

  return $opt;

}


//分月新聞
function archive_m($date=""){
  global $xoopsModuleConfig,$tadnews;

  if(empty($date)){
    $date=date("Y-m");
  }

  //$tadnews->set_show_num($xoopsModuleConfig['show_num']);
  $tadnews->set_news_kind("news");
  $tadnews->set_show_mode('list');
  $tadnews->set_show_month($date);
  $tadnews->set_show_enable(1);
  //$tadnews->get_news();

  $tnews=$tadnews->get_news('return');

  $date_title=to_utf8(str_replace("-",""._MD_TADNEWS_YEAR." ",$date)._MD_TADNEWS_MONTH._MD_TADNEWS_NEWS_TITLE);

  foreach($tnews['page'] as $news){

  $list.="<li><a href='{$_SERVER['PHP_SELF']}?nsn={$news['nsn']}'>{$news['post_date']} {$news['news_title']} <span class='ui-li-count'>{$news['counter']}</span></a></li>";
  }

  $list="
  <ul data-role='listview' data-inset='true' data-divider-theme='d'>
  <li data-role='list-divider'>{$date_title}</li>
  $list
  </ul>
  ";

  return $list;

}

//列出newspaper資料
function list_newspaper_m(){
  global $xoopsDB,$xoopsOption;

  $sql = "select a.npsn,a.number,b.title,a.np_date from ".$xoopsDB->prefix("tad_news_paper")." as a ,".$xoopsDB->prefix("tad_news_paper_setup")." as b where a.nps_sn=b.nps_sn and b.status='1' order by a.np_date desc";


  $result=$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));

  while(list($allnpsn,$number,$title,$np_date)=$xoopsDB->fetchRow($result)){

      $np_title=$title.sprintf(_MD_TADNEWS_NP_TITLE,$number);
      $np_date=substr($np_date,0,10);
      $main.="<li><a href='pda.php?op=preview&npsn={$allnpsn}'>{$np_date} {$np_title}</a></li>";

  }

  $main="
  <ul data-role='listview'>
  $main
  </ul>
  ";

  return $main;

}

//預覽電子報
function preview_newspaper_m($npsn=""){
  global $xoopsDB;
  if(empty($npsn))return;
  $np=get_newspaper($npsn);
  $sql = "select title,head,foot,themes from ".$xoopsDB->prefix("tad_news_paper_setup")." where nps_sn='{$np['nps_sn']}'";
  $result=$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));
  list($title,$head,$foot,$themes)=$xoopsDB->fetchRow($result);

  $head=str_replace('{N}',$np['number'],$head);
  $head=str_replace('{T}',$np['np_title'],$head);
  $head=str_replace('{D}',substr($np['np_date'],0,10),$head);

  $main="{$head}{$np['np_content']}{$foot}";

  return $main;
}

function login_m(){
  global $xoopsDB,$xoopsUser;

if($xoopsUser){
  $main="
<ul data-role='listview' data-theme='a' data-divider-theme='a' style='margin-top:-16px;'>
    <li data-icon='delete' style='background-color:#111;'>
      <a href='#' data-rel='close'>User Menu</a>
    </li>
    <li><a title='Administration Menu' href='".XOOPS_URL."/admin.php' rel='external'>Administration Menu</a></li>
    <li><a title='View Account' href='".XOOPS_URL."/user.php' rel='external'>View Account</a></li>
    <li><a title='Edit Account' href='".XOOPS_URL."/edituser.php' rel='external'>Edit Account</a></li>
    <li><a title='Notifications' href='".XOOPS_URL."/notifications.php' rel='external'>Notifications</a></li>
    <li><a title='Inbox' href='".XOOPS_URL."/viewpmsg.php' rel='external'>Inbox</a></li>
    <li><a title='Logout' href='".XOOPS_URL."/user.php?op=logout' rel='external'>Logout</a></li>
</ul>";
}else{
  $main="
<ul data-role='listview' data-theme='a' data-divider-theme='a' style='margin-top:-16px;'>
    <li data-icon='delete' style='background-color:#111;'>
      <a href='#' data-rel='close'>User Login</a>
    </li>
    <li>
<form method='post' action='".XOOPS_URL."/user.php' data-ajax='false'>
  User:<br>
  <input type='text' maxlength='25' value='' size='12' name='uname'>
   Password:<br>
  <input type='password' maxlength='32' size='12' name='pass'><br>
  <input type='hidden' value='/modules/tadnews/pda.php' name='xoops_redirect'>
  <input type='hidden' value='login' name='op'>
  <button type='submit' name='submit' value='Login'>Login</button><br>
</form>
</li>
</ul>
";
}
return $main;
}


/*-----------執行動作判斷區----------*/
$_REQUEST['op']=(empty($_REQUEST['op']))?"":$_REQUEST['op'];

$nsn=(isset($_REQUEST['nsn']))?intval($_REQUEST['nsn']) : 0;
$ncsn=(isset($_REQUEST['ncsn']))?intval($_REQUEST['ncsn']) : 0;
$date= (!isset($_REQUEST['date']))? date("Y-m"):substr($_REQUEST['date'],0,7);
$npsn=(empty($_GET['npsn']))?"":intval($_GET['npsn']);

switch($_REQUEST['op']){

  //下載檔案
  case "tufdl":
  $files_sn=isset($_GET['files_sn'])?intval($_GET['files_sn']):"";
  $TadUpFiles->add_file_counter($files_sn,$hash=false);
  exit;
  break;



  case "month_list":
    $main=month_list_m($date);
    $cate="Archive";
  break;

  case "archive":
    $main=archive_m($date);
    $cate="Archive";
  break;

  case "newspaper":
    $main=list_newspaper_m();
    $cate="ePaper";
  break;

  case "preview":
    $main=preview_newspaper_m($npsn);
    $cate="ePaper";
  break;

  default:
  $title=$xoopsModule->getVar('name');
  $cates=get_all_news_cate();
  $cate=(empty($cates[$ncsn]))?"$title":"$title-$cates[$ncsn]";
  if(!empty($nsn)){
    $main=show_news($nsn,$ncsn);
  }else{
    $main=list_tadnews($ncsn,$p);
  }
  break;
}


/*-----------秀出結果區--------------*/
//$title=$xoopsModule->getVar('name');
//分類下拉選單
$cate_list=get_tad_news_cate_list_m();
$login_m=login_m();

echo "
<!DOCTYPE HTML>
<html lang='"._LANGCODE."'>
<head>
<meta charset='"._CHARSET."'>
<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;' name='viewport'/>
<meta name='apple-mobile-web-app-capable' content='yes'/>
<title>$cate</title>
<link href='http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css' rel='stylesheet' type='text/css'/>
<link href='".XOOPS_URL."/modules/tadtools/bootstrap/css/bootstrap.css' rel='stylesheet' type='text/css'/>
<link href='".XOOPS_URL."/modules/tadtools/bootstrap/css/bootstrap-responsive.css' rel='stylesheet' type='text/css'/>
<script src='".XOOPS_URL."/modules/tadtools/jquery/jquery.js' type='text/javascript'></script>
<script>
$(document).bind('mobileinit', function()
{
  $.mobile.defaultPageTransition = 'slide';
});
</script>
<script>
$(document).bind('pagebeforeshow', function(){
var str=getUrlVars()['op'];
$('.nav li a.ui-btn-active').removeClass('ui-btn-active');
if (str == 'month_list' || str == 'archive')
{
  $('.nav li a#history').addClass('ui-btn-active').siblings().removeClass('ui-btn-active');
}
else if (str == 'newspaper' || str == 'preview')
{
  $('.nav li a#at').addClass('ui-btn-active').siblings().removeClass('ui-btn-active');
}
else
{
  $('.nav li a#home').addClass('ui-btn-active').siblings().removeClass('ui-btn-active');
}
});
function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}
</script>
<script src='http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.js' type='text/javascript'></script>
<style>
.ui-btn-right {
  /*top: -4px !important;*/
}
.ui-header .ui-title {
  margin: 0.6em 0% 0.8em !important;
}
h1, h2, h3 {
    line-height: 1.1em;
}
.ui-bar-b {
  opacity:0.9;
}
input.ui-input-text {
  font-size: 14px;
}

.nav .ui-btn .ui-btn-inner {
    padding-top: 30px !important;
}
.nav .ui-btn .ui-icon {
    border-radius: 0 0 0 0 !important;
    box-shadow: none !important;
    height: 30px !important;
    margin-left: -15px !important;
    width: 30px !important;
}
#home .ui-icon {
    background: url('images/icons/mobile_icons/home.png') no-repeat scroll 50% 50% transparent;
    background-size: 24px 24px;
}
#history .ui-icon {
    background: url('images/icons/mobile_icons/history.png') no-repeat scroll 50% 50% transparent;
    background-size: 24px 24px;
}
#at .ui-icon {
    background: url('images/icons/mobile_icons/at.png') no-repeat scroll 50% 50% transparent;
    background-size: 24px 24px;
}
#setting .ui-icon {
    background: url('images/icons/mobile_icons/setting.png') no-repeat scroll 50% 50% transparent;
    background-size: 24px 24px;
}
@media only screen and (-webkit-min-device-pixel-ratio: 2) {
#home .ui-icon {
    background: url('images/icons/mobile_icons/home.png') no-repeat scroll 50% 50% transparent;
    background-size: 24px 24px;
}
#history .ui-icon {
    background: url('images/icons/mobile_icons/history.png') no-repeat scroll 50% 50% transparent;
    background-size: 24px 24px;
}
#at .ui-icon {
    background: url('images/icons/mobile_icons/at.png') no-repeat scroll 50% 50% transparent;
    background-size: 24px 24px;
}
#setting .ui-icon {
    background: url('images/icons/mobile_icons/setting.png') no-repeat scroll 50% 50% transparent;
    background-size: 24px 24px;
}
}
</style>
</head>
<body>
<div data-role='page' id='page_{$nsn}' data-add-back-btn='true'>
  <div data-role='header' data-id='header' data-theme='a' data-position='fixed'>
  <h1>$cate</h1>
  <a href='#category' data-icon='bars' data-iconpos='notext' class='ui-btn-right'>Menu</a>
  </div>
  <div data-role='content' id='content'>
  $main
  </div>
<div data-role='footer' data-id='footer' data-position='fixed'>
  <div data-role='navbar' class='nav'>
    <ul>
      <li><a href='{$_SERVER['PHP_SELF']}' id='home' data-icon='custom'>News</a></li>
      <li><a href='{$_SERVER['PHP_SELF']}?op=month_list' id='history' data-icon='custom'>Archive</a></li>
      <li><a href='{$_SERVER['PHP_SELF']}?op=newspaper' id='at' data-icon='custom'>ePaper</a></li>
      <li><a href='#more' id='setting' data-icon='custom'>More</a></li>
    </ul>
  </div><!-- /navbar -->
</div><!-- /footer -->
<div data-role='panel' data-position='right' data-position-fixed='false' data-display='push' id='category' data-theme='a'>
$cate_list
</div>
<div data-role='panel' data-position='right' data-position-fixed='false' data-display='push' id='more' data-theme='a'>
$login_m
</div>
</div><!-- /page -->
</body>
</html>
";
?>
