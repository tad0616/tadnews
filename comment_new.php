<?php
// $Id: comment_new.php,v 1.1 2008/04/10 05:31:02 tad Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
include '../../mainfile.php';

$com_itemid = isset($_GET['com_itemid']) ? intval($_GET['com_itemid']) : 0;

if ($com_itemid > 0) {
  $sql="select news_title,news_content from ".$xoopsDB->prefix("tad_news")." where nsn='{$com_itemid}'";
	$result=$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, show_error($sql));
	list($title,$content)=$xoopsDB->fetchRow($result);
	$com_replytext=$content;
}

$com_replytitle = "RE:{$title}";


include XOOPS_ROOT_PATH.'/include/comment_new.php';
?>
