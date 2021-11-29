<div class="panel panel-default card">
    <table class="table table-striped">
        <{foreach item=topic from=$block.re}>
            <tr>
                <td class="text-right text-end">
                    <a href='<{$xoops_url}>/userinfo.php?uid=<{$topic.uid}>'><{$topic.uid_name}></a> :
                </td>
                <td class="text-left text-start">
                    <a href='<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$topic.nsn}>#comment<{$topic.com_id}>'><{$topic.txt}></a>
                </td>
            </tr>
        <{/foreach}>
    </table>
</div>