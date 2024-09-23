<h2 class="my">
    <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$nsn|default:''}>"><{$news_title|default:''}></a>
</h2>

<div class="panel panel-default card">
    <table class="table table-bordered">
        <{foreach from=$sign item=data}>
        <tr>
            <td><a href="index.php?uid=<{$data.uid}>&op=list_user_sign"><{$data.uid_name}></a></td>
            <td><{$data.sign_time}></td>
        </tr>
        <{/foreach}>
    </table>
</div>