<h2 class="my">
    <a href="<{$xoops_url}>/userinfo.php?uid=<{$uid|default:''}>"><{$uid_name|default:''}></a>
</h2>

<div class="panel panel-default card">
    <table class="table table-bordered">
        <{foreach from=$sign item=data}>
            <tr>
                <td>[<{$data.nsn}>] <a href='<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$data.nsn}>'><{$data.news_title}></a></td>
                <td><{$data.sign_time}></td>
            </tr>
        <{/foreach}>
    </table>
</div>