<h2 class="my">
    <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$nsn}>"><{$news_title}></a>
</h2>

<div class="panel panel-default card">
    <table class="table table-bordered">
        <{foreach item=sign from=$sign}>
        <tr>
            <td><a href="index.php?uid=<{$sign.uid}>&op=list_user_sign"><{$sign.uid_name}></a></td>
            <td><{$sign.sign_time}></td>
        </tr>
        <{/foreach}>
    </table>
</div>