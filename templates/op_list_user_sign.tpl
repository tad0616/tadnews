<h2 class="my">
    <a href="<{$xoops_url}>/userinfo.php?uid=<{$uid}>"><{$uid_name}></a>
</h2>

<div class="panel panel-default card">
    <table class="table table-bordered">
        <{foreach item=sign from=$sign}>
            <tr>
                <td>[<{$sign.nsn}>] <a href='<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$sign.nsn}>'><{$sign.news_title}></a></td>
                <td><{$sign.sign_time}></td>
            </tr>
        <{/foreach}>
    </table>
</div>