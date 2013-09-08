<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2007-11-04
// $Id: tadnews_cate.php,v 1.2 2008/06/25 06:36:39 tad Exp $
// ------------------------------------------------------------------------- //

//區塊主函式 (顯示所有新聞的類別)
function tadnews_cate_show($options){
  if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/dtree.php")){
  	redirect_header("index.php",3, _MA_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/dtree.php";
  $cate=block_get_news_cate();
  $home['sn']=0;
  $home['title']=_MB_TADNEWS_NO_CATE;
  $home['url']=XOOPS_URL."/modules/tadnews/index.php?ncsn=0";
  $dtree=new dtree("tadnews_cate_tree",$home,$cate['title'],$cate['of_ncsn'],$cate['url']);
  $block=$dtree->render();
	return $block;
}


//取得所有類別標題
if(!function_exists("block_get_news_cate")){
  function block_get_news_cate(){
		global $xoopsDB;

		$sql="select ncsn,of_ncsn,nc_title from ".$xoopsDB->prefix("tad_news_cate")." where not_news!='1' order by sort";
		$result=$xoopsDB->query($sql);
		while(list($ncsn,$of_ncsn,$nc_title)=$xoopsDB->fetchRow($result)){

			$cate['title'][$ncsn]=$nc_title;
      $cate['of_ncsn'][$ncsn]=$of_ncsn;
      $cate['url'][$ncsn]=XOOPS_URL."/modules/tadnews/index.php?ncsn={$ncsn}";
		}
		return $cate;
  }
}

?>
