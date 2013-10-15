<?php
include_once XOOPS_ROOT_PATH."/modules/tadnews/block_function.php";

//區塊主函式 (顯示類別新聞)
function tadnews_cate_news($options){
  //$block=list_block_cate_news($options[0],$options[1],$options[2],$options[3],$options[4],$options[5]);
  global $xoopsDB,$xoopsModule,$xoopsUser,$xoopsOption,$xoopsModuleConfig,$xoopsTpl;

  $ncsn_arr=explode(',',$options[0]);

  include_once XOOPS_ROOT_PATH."/modules/tadnews/class/tadnews.php";
	$tadnews=new tadnews();
	$tadnews->set_news_kind("news");
	$tadnews->set_show_mode('cate');
	$tadnews->set_show_num($options[1]);
	$tadnews->set_summary($options[4],$options[5]);
	$tadnews->set_cover($options[2]);
	$tadnews->set_view_ncsn($ncsn_arr);
	$block=$tadnews->get_cate_news('return');
  if(empty($block['all_news']))return;

  $block['bootstrap']=get_bootstrap();
  $block['show_line']=($options[3]=='1')?"table":"";

	return $block;
}

//區塊編輯函式
function tadnews_cate_news_edit($options){
  $option=block_news_cate($options[0]);

	$form="{$option['js']}
	<table style='width:auto;'>
	<tr><th>1.</th><th>"._MB_TADNEWS_CATE_NEWS_EDIT_BITEM0."</th><td>{$option['form']}
	<INPUT type='hidden' name='options[0]' id='bb' value='{$options[0]}'></td></tr>
	<tr><th>2.</th><th>"._MB_TADNEWS_CATE_NEWS_EDIT_BITEM1."</th><td><INPUT type='text' name='options[1]' value='{$options[1]}' size=3></td></tr>
	<tr><th>3.</th><th>"._MB_TADNEWS_CATE_NEWS_EDIT_BITEM2."</th><td>
  <INPUT type='radio' name='options[2]' value='1' ".chk($options[2],1,"1").">"._YES."
  <INPUT type='radio' name='options[2]' value='0' ".chk($options[2],0).">"._NO."</td></tr>
  <tr><th>4.</th><th>"._MB_TADNEWS_CATE_NEWS_EDIT_BITEM3."</th><td>
  <INPUT type='radio' name='options[3]' value='1' ".chk($options[3],1,"1").">"._YES."
  <INPUT type='radio' name='options[3]' value='0' ".chk($options[3],0).">"._NO."</td></tr>
  <tr><th>5.</th><th>"._MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM1."</th><td>
	<INPUT type='text' name='options[4]' value='{$options[4]}'></td></tr>
  <tr><th>6.</th><th>"._MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM2."</th><td>
	<textarea name='options[5]' style='width:400px;height:50px;'>{$options[5]}</textarea>
  </td></tr>
	</table>
	";
	return $form;
}



//單選回復原始資料函數
if(!function_exists("chk")){
  function chk($DBV="",$NEED_V="",$defaul="",$return="checked"){
  	if($DBV==$NEED_V){
  		return $return;
  	}elseif(empty($DBV) && $defaul=='1'){
  		return $return;
  	}
  	return "";
  }
}

?>
