<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2007-11-04
// $Id: tadnews_page.php,v 1.1 2008/06/25 06:36:34 tad Exp $
// ------------------------------------------------------------------------- //

//區塊主函式 (顯示指定的自訂類別文章)
function tadnews_page($options){
  global $xoopsDB;
  if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/dtree.php")){
  	redirect_header("index.php",3, _MB_NEED_TADTOOLS);
  }
  
  include_once XOOPS_ROOT_PATH."/modules/tadtools/dtree.php";
  if(empty($options[0]))return;
	
  $sql="select ncsn,of_ncsn,nc_title from ".$xoopsDB->prefix("tad_news_cate")." where ncsn='{$options[0]}'";
	$result=$xoopsDB->query($sql);
	list($ncsn,$of_ncsn,$nc_title)=$xoopsDB->fetchRow($result);
	
	$home['sn']=$ncsn;
	$home['title']=$nc_title;
	$home['url']=XOOPS_URL."/modules/tadnews/index.php?ncsn={$ncsn}";

  $page=block_get_page_cate($options[0]);

  $dtree=new dtree("tadnews_mypage_tree{$ncsn}",$home,$page['title'],$page['of_ncsn'],$page['url']);
  $block=$dtree->render();
	return $block;
}


//區塊編輯函式
function tadnews_page_edit($options){
	$cate=block_get_all_not_news_cate(0,$options[0]);

	$form="
	"._MB_TADNEWS_PAGE_EDIT_BITEM0."
	<select name='options[0]'>
	$cate
	</select>
	<br />
	"._MB_TADNEWS_PAGE_EDIT_BITEM1."
	<INPUT type='text' name='options[1]' value='{$options[1]}' size=3> px
	";
	return $form;
}


//取得所有類別標題
if(!function_exists("block_get_all_not_news_cate")){
	function block_get_all_not_news_cate($of_ncsn=0,$default_ncsn=0,$level=0){
		global $xoopsDB,$xoopsUser,$xoopsModule;

		$left=$level*10;
		$level+=1;

		$option=($of_ncsn)?"":"<option value='0'></option>";
		$sql = "select ncsn,nc_title from ".$xoopsDB->prefix("tad_news_cate")." where not_news='1' and of_ncsn='{$of_ncsn}' order by sort";
		$result = $xoopsDB->query($sql);

		while(list($ncsn,$nc_title)=$xoopsDB->fetchRow($result)){
			$selected=($default_ncsn==$ncsn)?"selected":"";
			$option.="<option value='{$ncsn}' style='padding-left: {$left}px' $selected>{$nc_title}</option>";
			$option.=block_get_all_not_news_cate($ncsn,$default_ncsn,$level);
		}
		return $option;
	}
}



//樹狀選項
if(!function_exists("block_get_page_cate")){
	function block_get_page_cate($the_ncsn=0){
		global $xoopsDB;
		
		$sql="select ncsn,of_ncsn,nc_title from ".$xoopsDB->prefix("tad_news_cate")." where ncsn='$the_ncsn' or of_ncsn='$the_ncsn' order by sort";
		$result=$xoopsDB->query($sql);
		$i=10000;
    $myts =MyTextSanitizer::getInstance();
		while(list($ncsn,$of_ncsn,$nc_title)=$xoopsDB->fetchRow($result)){
      if($the_ncsn!=$ncsn){
  			$page['title'][$ncsn]=$nc_title;
        $page['of_ncsn'][$ncsn]=$of_ncsn;
        $page['url'][$ncsn]=XOOPS_URL."/modules/tadnews/index.php?ncsn={$ncsn}";
      }
      $sql2="select nsn,ncsn,news_title from ".$xoopsDB->prefix("tad_news")." where ncsn='$ncsn' order by page_sort";
		  $result2=$xoopsDB->query($sql2);
      while(list($nsn,$ncsn,$news_title)=$xoopsDB->fetchRow($result2)){
        $myts->htmlSpecialChars($news_title);
  			$page['title'][$i]=$news_title;
        $page['of_ncsn'][$i]=$ncsn;
        $page['url'][$i]=XOOPS_URL."/modules/tadnews/page.php?nsn={$nsn}";
        $i++;
  		}
		}
		return $page;
	}
}




?>
