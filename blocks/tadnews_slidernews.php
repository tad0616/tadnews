<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2007-11-04
// $Id: tadnews_newspaper_list.php,v 1.1 2008/04/10 05:29:56 tad Exp $
// ------------------------------------------------------------------------- //

include_once XOOPS_ROOT_PATH."/modules/tadnews/block_function.php";

//區塊主函式 (滑動新聞)
function tadnews_slidernews_show($options){
	global $xoopsDB,$xoopsModule,$xoopsUser,$xoopsOption;
	
  if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/lofslidernews.php")){
  	redirect_header("index.php",3, _MA_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/lofslidernews.php";

	$ncsn_arr=explode(',',$options[4]);

  include_once XOOPS_ROOT_PATH."/modules/tadnews/class/tadnews.php";

	$tadnews=new tadnews();

	$tadnews->set_show_num($options[2]);
	$tadnews->set_view_ncsn($ncsn_arr);
	$tadnews->set_show_mode('summary');
	$tadnews->set_news_kind("news");
	$tadnews->set_summary($options[3]);
  $tadnews->set_use_star_rating(false);
	$tadnews->set_cover(false);
	$all_news=$tadnews->get_news('return');

  if(empty($all_news['page']))return _TADNEWS_EMPTY;

	$n=0;
  $lofslidernews=new lofslidernews($options[0],$options[1],$options[3]);

  foreach($all_news['page'] as $news){
    $lofslidernews->add_content($news['nsn'],$news['news_title'],$news['content'],$news['image_big'],$news['post_date'],XOOPS_URL."/modules/tadnews/index.php?nsn={$news['nsn']}");
    $n++;
    if($n>=$options[2])break;
	}

	$block=$lofslidernews->render();

	return $block;
}

//區塊編輯函式
function tadnews_slidernews_edit($options){


  $block_news_cate=block_news_cate($options[4]);
  
	$form="{$block_news_cate['js']}
	<table style='width:auto;'>
	<tr><th>
	"._MB_TADNEWS_SLIDERNEWS_BLOCK_EDIT_BITEM0."
	</th><td>
	<INPUT type='text' name='options[0]' value='{$options[0]}' size=6>px
  </td></tr>
	<tr><th>
	"._MB_TADNEWS_SLIDERNEWS_BLOCK_EDIT_BITEM1."
	</th><td>
	<INPUT type='text' name='options[1]' value='{$options[1]}' size=6>px
  </td></tr>
	<tr><th>
	"._MB_TADNEWS_SLIDERNEWS_BLOCK_EDIT_BITEM2."
	</th><td>
	<INPUT type='text' name='options[2]' value='{$options[2]}' size=6>
  </td></tr>
	<tr><th>
	"._MB_TADNEWS_SLIDERNEWS_BLOCK_EDIT_BITEM3."
	</th><td>
	<INPUT type='text' name='options[3]' value='{$options[3]}' size=6>
  </td></tr>
  <tr><th>"._MB_TADNEWS_CATE_NEWS_EDIT_BITEM0."</th><td>{$block_news_cate['form']}
	<INPUT type='hidden' name='options[4]' id='bb' value='{$options[4]}'></td></tr>
	</table>
	";
	return $form;
}

?>