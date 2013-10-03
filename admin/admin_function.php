<?php

//列出所有tad_news資料（$kind="news","page"）
function list_tad_news($the_ncsn="0",$kind="news",$show_uid=""){
	global $xoopsDB,$xoopsModule,$xoopsUser,$xoopsOption,$xoopsModuleConfig;


	$tadnews=new tadnews();
	if(!empty($show_uid)){
	 $tadnews->set_view_uid($show_uid);
	}
	$tadnews->set_news_kind($kind);
  $tadnews->set_summary(0);
	$tadnews->set_show_mode("list");
	$tadnews->set_admin_tool(true);
	$tadnews->set_show_num($xoopsModuleConfig['show_num']);
	$tadnews->set_show_enable(0);
	$tadnews->set_news_cate_select(1);
	$tadnews->set_news_author_select(1);
	$tadnews->set_news_check_mode(1);

	if(!empty($the_ncsn)){
		$tadnews->set_view_ncsn($the_ncsn);
    if($kind=="page"){
		  $tadnews->set_sort_tool(1);
		}
	}
	$tadnews->get_news();

}


//列出所有tad_news_cate資料
function list_tad_news_cate($of_ncsn=0,$level=0,$not_news='0',$i=0,$catearr=""){
	global $xoopsDB,$xoopsModule,$xoopsTpl;
	$old_level=$level;
	$left=$level*18+4;
	$level++;


	$sql = "select * from ".$xoopsDB->prefix("tad_news_cate")." where not_news='{$not_news}' and of_ncsn='{$of_ncsn}' order by sort";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, show_error($sql));


	//$catearr="";

  //$i=0;
	while(list($ncsn,$of_ncsn,$nc_title,$enable_group,$enable_post_group,$sort,$cate_pic,$not_news)=$xoopsDB->fetchRow($result)){

	  $sql2 = "select count(*) from ".$xoopsDB->prefix("tad_news")." where ncsn='{$ncsn}'";
		$result2 = $xoopsDB->query($sql2);
		list($counter)=$xoopsDB->fetchRow($result2);

    $pic=(empty($cate_pic))?"../images/no_cover.png":_TADNEWS_CATE_URL."/{$cate_pic}";
		$g_txt=tadnews::txt_to_group_name($enable_group,_TADNEWS_ALL_OK," , ");
		$gp_txt=tadnews::txt_to_group_name($enable_post_group,_MA_TADNEWS_ONLY_ROOT," , ");

		$new_kind=($not_news=='1')?0:1;
		$change_text=($not_news=='1')?_MA_TADNEWS_CHANGE_TO_NEWS:_MA_TADNEWS_CHANGE_TO_PAGE;


		$catearr[$i]['left']=$left;
		$catearr[$i]['pic']=$pic;
		$catearr[$i]['nc_title']=$nc_title;
		$catearr[$i]['sort']=$sort;
		$catearr[$i]['ncsn']=$ncsn;
		$catearr[$i]['counter']=$counter;
		$catearr[$i]['g_txt']=$g_txt;
		$catearr[$i]['gp_txt']=$gp_txt;
		$catearr[$i]['new_kind']=$new_kind;
		$catearr[$i]['change_text']=$change_text;
		$catearr[$i]['offset']=empty($old_level)?"":"offset{$old_level}";

		$i++;

		/*
		echo "<div>\$catearr[$i]['nc_title']=$nc_title;</div>";
		echo "<div>\$catearr[$i]['sort']=$sort;</div>";
		echo "<div>\$catearr[$i]['ncsn']=$ncsn;</div>";
		echo "<hr>";
		*/


    $sub=list_tad_news_cate($ncsn,$level,$not_news,$i,$catearr);
    $i=$sub['i'];
    if(!empty($sub['arr']))$catearr=$sub['arr'];
	}
  //$xoopsTpl->assign( "cate" , $catearr) ;
  $all['i']=$i;
  $all['arr']=$catearr;
	return $all;
}




//縮圖上傳
function mk_thumb($ncsn="",$col_name="",$width=100){
	global $xoopsDB;
	include XOOPS_ROOT_PATH."/modules/tadtools/upload/class.upload.php";

	if(file_exists(_TADNEWS_CATE_DIR."/{$ncsn}.png")){
		unlink(_TADNEWS_CATE_DIR."/{$ncsn}.png");
	}

  $handle = new upload($_FILES[$col_name]);
  if ($handle->uploaded) {
      $handle->file_new_name_body   = $ncsn;
      $handle->image_convert = 'png';
      $handle->image_resize         = true;
      $handle->image_x              = $width;
      $handle->image_ratio_y        = true;
      $handle->file_overwrite 			= true;
      $handle->process(_TADNEWS_CATE_DIR);
      $handle->auto_create_dir = true;
      if ($handle->processed) {
          $handle->clean();
          $sql = "update ".$xoopsDB->prefix("tad_news_cate")." set  cate_pic = '{$ncsn}.png' where ncsn='$ncsn'";
					$xoopsDB->queryF($sql);
          return true;
      }
  }
  return false;
}


//新增資料到tad_news_cate中
function insert_tad_news_cate(){
	global $xoopsDB,$xoopsModuleConfig;
	if(empty($_POST['enable_group']) or in_array("",$_POST['enable_group'])){
    $enable_group="";
	}else{
		$enable_group=implode(",",$_POST['enable_group']);
	}
	$enable_post_group=implode(",",$_POST['enable_post_group']);

	foreach($_POST['setup'] as $key=>$val){
		$setup.="{$key}=$val;";
	}
	$setup=substr($setup,0,-1);

  $myts =MyTextSanitizer::getInstance();
  $nc_title=$myts->addSlashes($_POST['nc_title']);


	$sql = "insert into ".$xoopsDB->prefix("tad_news_cate")." (of_ncsn,nc_title,enable_group,enable_post_group,sort,not_news,setup) values('{$_POST['of_ncsn']}','{$nc_title}','{$enable_group}','{$enable_post_group}','{$_POST['sort']}','{$_POST['not_news']}','{$setup}')";
	$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _TADNEWS_DB_ADD_ERROR1);
	//取得最後新增資料的流水編號
	$ncsn=$xoopsDB->getInsertId();

	if(!empty($_FILES['cate_pic'])){
    mk_thumb($ncsn,"cate_pic",$xoopsModuleConfig['cate_pic_width']);
	}

	return $ncsn;
}


//更新tad_news_cate某一筆資料
function update_tad_news_cate($ncsn=""){
	global $xoopsDB,$xoopsModuleConfig;
	if(empty($_POST['enable_group']) or in_array("",$_POST['enable_group'])){
    $enable_group="";
	}else{
		$enable_group=implode(",",$_POST['enable_group']);
	}
	$enable_post_group=implode(",",$_POST['enable_post_group']);

	foreach($_POST['setup'] as $key=>$val){
		$setup.="{$key}=$val;";
	}
	$setup=substr($setup,0,-1);

	$sql = "update ".$xoopsDB->prefix("tad_news_cate")." set  of_ncsn = '{$_POST['of_ncsn']}', nc_title = '{$_POST['nc_title']}', enable_group = '{$enable_group}', enable_post_group = '{$enable_post_group}', sort = '{$_POST['sort']}',not_news='{$_POST['not_news']}',setup='{$setup}' where ncsn='$ncsn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MA_TADNEWS_DB_UPDATE_ERROR1."<br>$sql");

	if(!empty($_FILES['cate_pic']['name'])){
    mk_thumb($ncsn,"cate_pic",$xoopsModuleConfig['cate_pic_width']);
	}

	return $ncsn;
}



//刪除tad_news_cate某筆資料資料
function delete_tad_news_cate($ncsn=""){
	global $xoopsDB;

	$cate_org=tadnews::get_tad_news_cate($ncsn);

	//先找看看底下有無分類，若有將其父分類變成原分類之父分類
	$sql = "update ".$xoopsDB->prefix("tad_news_cate")."  set  of_ncsn = '{$cate_org['of_ncsn']}' where of_ncsn='$ncsn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MA_TADNEWS_DB_DEL_ERROR1."<br>$sql");


	$sql = "delete from ".$xoopsDB->prefix("tad_news_cate")." where ncsn='$ncsn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MA_TADNEWS_DB_DEL_ERROR1);
}


//轉換分類類型
function change_kind($ncsn="",$not_news=""){
	global $xoopsDB,$xoopsModuleConfig;

  $sql = "update ".$xoopsDB->prefix("tad_news_cate")." set not_news='{$not_news}' , of_ncsn='0' where ncsn ='{$ncsn}'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MA_TADNEWS_DB_UPDATE_ERROR1."<br>$sql");

	//先找看看底下有無分類，若有將其也一起變
  $sub_cate=get_sub_cate($ncsn);
  $where=empty($sub_cate)?"where ncsn ='{$ncsn}'":"where ncsn in ($sub_cate)";

  $sql = "update ".$xoopsDB->prefix("tad_news_cate")." set not_news='{$not_news}' $where";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MA_TADNEWS_DB_UPDATE_ERROR1."<br>$sql");
  if($not_news==1){
    header("location: page_cate.php");
  }else{
    header("location: cate.php");
  }
}


//找出底下的子分類
function get_sub_cate($of_ncsn=""){
	global $xoopsDB;
  $sql = "select ncsn from ".$xoopsDB->prefix("tad_news_cate")." where of_ncsn='$of_ncsn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));
	//echo "<p>$sql</p>";
	while(list($sub_ncsn)=$xoopsDB->fetchRow($result)){
    $ccc=get_sub_cate($sub_ncsn);
    if(!empty($ccc))$aaa[]=$ccc;
    $aaa[]=$sub_ncsn;
  }
  $bbb=implode(',',$aaa);
  //echo "<p style='color:red;'>$bbb</p>";

  return $bbb;
}




//搬移文章
function move_to_cate($ncsn="",$to_ncsn=""){
	global $xoopsDB;

  $sql = "update ".$xoopsDB->prefix("tad_news")." set ncsn='{$to_ncsn}' where ncsn='{$ncsn}'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, show_error($sql));
  return;
}

//批次移動
function move_news($nsn_arr=array(),$ncsn=""){
	global $xoopsDB;
	if(empty($nsn_arr) or !is_array($nsn_arr))return;

  foreach($nsn_arr as $nsn){
    $sql = "update ".$xoopsDB->prefix("tad_news")." set ncsn='{$ncsn}' where nsn='{$nsn}'";
  	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, show_error($sql));
	}
  return;
}

//批次刪除
function del_news($nsn_arr=array()){
	global $xoopsDB;
	if(empty($nsn_arr) or !is_array($nsn_arr))return;

  foreach($nsn_arr as $nsn){
    $sql = "delete from ".$xoopsDB->prefix("tad_news")." where nsn='{$nsn}'";
  	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, show_error($sql));
	}
  return;
}
?>
