<?php
function tadnews_search($queryarray, $andor, $limit, $offset, $userid){
	global $xoopsDB;
	//處理許功蓋
	if(get_magic_quotes_gpc()){
		if(is_array($queryarray)){
			foreach($queryarray as $k=>$v){
				$arr[$k]=addslashes($v);
			}
			$queryarray=$arr;
		}else{
			$queryarray=array();
		}
	}
	$sql = "SELECT nsn,news_title,start_day, uid FROM ".$xoopsDB->prefix("tad_news")." WHERE enable='1'";
	if ( $userid != 0 ) {
		$sql .= " AND uid=".$userid." ";
	}
	if ( is_array($queryarray) && $count = count($queryarray) ) {
		$sql .= " AND ((news_title LIKE '%$queryarray[0]%' OR news_content LIKE '%$queryarray[0]%')";
		for($i=1;$i<$count;$i++){
			$sql .= " $andor ";
			$sql .= "( news_title LIKE '%$queryarray[$i]%' OR news_content LIKE '%$queryarray[$i]%')";
		}
		$sql .= ") ";
	}
	$sql .= "ORDER BY start_day DESC";
	$result = $xoopsDB->query($sql,$limit,$offset);
	$ret = array();
	$i = 0;
 	while($myrow = $xoopsDB->fetchArray($result)){
		$ret[$i]['image'] = "images/dot.gif";
		$ret[$i]['link'] = "index.php?nsn=".$myrow['nsn'];
		$ret[$i]['title'] = $myrow['news_title'];
		$ret[$i]['time'] = strtotime($myrow['start_day']);
		$ret[$i]['uid'] = $myrow['uid'];
		$i++;
	}
	return $ret;
}

?>
