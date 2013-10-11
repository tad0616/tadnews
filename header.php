<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2007-11-04
// $Id: header.php,v 1.1 2008/04/10 05:31:02 tad Exp $
// ------------------------------------------------------------------------- //

include_once "../../mainfile.php";

if($xoopsModuleConfig['use_pda']=='1'){
  if(file_exists(XOOPS_ROOT_PATH."/modules/tadtools/mobile_device_detect.php")){
    include_once XOOPS_ROOT_PATH."/modules/tadtools/mobile_device_detect.php";
    mobile_device_detect(true,false,true,true,true,true,true,'pda.php',false);
  }
}

include_once "function.php";

if ($xoopsUser) {
	$module_id = $xoopsModule->getVar('mid');
	$isAdmin=$xoopsUser->isAdmin($module_id);
}else{
  $isAdmin=false;
}

$interface_menu[_TADNEWS_NAME]="index.php";
if($xoopsModuleConfig['use_archive']=='1')  $interface_menu[_MD_TADNEWS_ARCHIVE]="archive.php";
if($xoopsModuleConfig['use_newspaper']=='1')  $interface_menu[_MD_TADNEWS_NEWSPAPER]="newspaper.php";


$p=tadnews::chk_user_cate_power();
if(sizeof($p)>0 and $xoopsUser){
  $and_ncsn=empty($_REQUEST['ncsn'])?"":"?ncsn={$_REQUEST['ncsn']}";
	$interface_menu[_MD_TADNEWS_POST]="post.php{$and_ncsn}";
  $interface_menu[_MD_TADNEWS_MY]="my_news.php";
}

if($isAdmin){
  $interface_menu[_MD_TADNEWS_TO_ADMIN]="admin/main.php";
}

?>
