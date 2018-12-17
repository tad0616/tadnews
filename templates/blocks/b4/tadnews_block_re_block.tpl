<table class="table table-striped">
<{foreach item=topic from=$block.re}>
  <tr>
  	<td class="text-right"><a href='<{$xoops_url}>/userinfo.php?uid=<{$topic.uid}>'><{$topic.uid_name}></a> : </td>
  	<td class="text-left"><a href='<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$topic.nsn}>#comment<{$topic.com_id}>'><{$topic.txt}></a></td>
  </tr>
<{/foreach}>
</table>