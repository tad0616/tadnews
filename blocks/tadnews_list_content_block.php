<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2007-11-04
// $Id: tadnews_content_block.php,v 1.4 2008/06/25 06:36:39 tad Exp $
// ------------------------------------------------------------------------- //
include_once XOOPS_ROOT_PATH."/modules/tadnews/block_function.php";

//區塊主函式 (顯示新聞內容)
function tadnews_list_content_block_show($options){
	global $xoopsDB,$xoopsModule,$xoopsUser,$xoopsOption,$xoopsModuleConfig,$xoopsTpl;

	$ncsn_arr=explode(',',$options[7]);

  include_once XOOPS_ROOT_PATH."/modules/tadnews/class/tadnews.php";

	$tadnews=new tadnews();

	$tadnews->set_show_num($options[0]);
	$tadnews->set_view_ncsn($ncsn_arr);
	$tadnews->set_show_mode('list');
	$tadnews->set_news_kind("news");
	$tadnews->set_summary($options[1],$options[2]);
	$tadnews->set_title_length($options[3]);
	$tadnews->set_cover($options[4],$options[5]);
	$tadnews->set_skip_news($options[6]);
	$block=$tadnews->get_news('return');
  $block['bootstrap']=get_bootstrap();

	return $block;
}

//區塊編輯函式
function tadnews_list_content_block_edit($options){

  $options4_1=($options[4]=="1")?"checked":"";
  $options4_0=($options[4]=="0")?"checked":"";

  $option=block_news_cate($options[7]);

	$form="{$option['js']}
	<table>
	<tr><th>
	"._MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM0."
	</th><td>
	<INPUT type='text' name='options[0]' value='{$options[0]}' size=6>
  </td></tr>
	<tr><th>
	"._MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM1."
	</th><td>
	<INPUT type='text' name='options[1]' value='{$options[1]}' size=6>"._MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM1_DESC."
  </td></tr>
	<tr><th>
	"._MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM2."
	</th><td>
	<textarea name='options[2]' style='width:400px;height:40px;font-family:Arial;font-size:13px;'>{$options[2]}</textarea>
	<div>ex: <span style='color:#0066CC;font-size:11px;'>color:gray;font-size:11px;margin-top:3px;line-height:150%;</span></div>
  </td></tr>
	<tr><th>
	"._MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM3."
	</th><td>
	<INPUT type='text' name='options[3]' value='{$options[3]}' size=6>"._MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM3_DESC."
  </td></tr>
	<tr><th>
	"._MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM4."
	</th><td>
	<INPUT type='radio' name='options[4]' value='1' $options4_1>"._YES."
	<INPUT type='radio' name='options[4]' value='0' $options4_0>"._NO."
  </td></tr>
	<tr><th>
	"._MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM5."
	</th><td>
	<textarea name='options[5]' style='width:400px;height:40px;font-family:Arial;font-size:13px;'>{$options[5]}</textarea>
	<div>ex: <span style='color:#0066CC;font-size:11px;'>width:60px;height:30px;float:left;border:1px solid #9999CC;margin:0px 4px 4px 0px;</span></div>
  </td></tr>
	<tr><th>
	"._MB_TADNEWS_START_FROM."
	</th><td>
	<INPUT type='text' name='options[6]' value='{$options[6]}' size=6>
  </td></tr>
	<tr><th>"._MB_TADNEWS_CATE_NEWS_EDIT_BITEM0."</th><td>{$option['form']}
	<INPUT type='hidden' name='options[7]' id='bb' value='{$options[7]}'></td></tr>
	</table>
	";
	return $form;
}

?>
