<p>
    <{$toolbar}>
</p>


<{if $op=="list_sign"}>
    <h1>
        <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$nsn}>"><{$news_title}></a>
    </h1>

    <table class="table table-bordered">
        <{foreach item=sign from=$sign}>
        <tr>
            <td><a href="index.php?uid=<{$sign.uid}>&op=list_user_sign"><{$sign.uid_name}></a></td>
            <td><{$sign.sign_time}></td>
        </tr>
        <{/foreach}>
    </table>

<{elseif $op=="list_user_sign"}>
    <h1>
        <a href="<{$xoops_url}>/userinfo.php?uid=<{$uid}>"><{$uid_name}></a>
    </h1>

    <table class="table table-bordered">
        <{foreach item=sign from=$sign}>
            <tr>
                <td>[<{$sign.nsn}>] <a href='<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$sign.nsn}>'><{$sign.news_title}></a></td>
                <td><{$sign.sign_time}></td>
            </tr>
        <{/foreach}>
    </table>
<{/if}>