<{if $page}>
    <div class="panel panel-default card">
        <table class="table table-striped">
            <tbody>
                <{foreach item=page from=$page}>
                    <tr>
                        <td>
                            <{$page.chkbox}>
                            <{$page.post_date}>

                            <{$page.prefix_tag}>
                            <{if $page.need_sign}>
                                <img src="<{$page.need_sign}>" alt="<{$page.news_title}>" style="margin:3px;">
                            <{/if}>
                            <{$page.enable_txt}>
                            <{$page.today_pic}>
                            <a href="<{$xoops_url}>/modules/tadnews/<{$page.link_page}>?nsn=<{$page.nsn}>"><{$page.news_title}></a>
                            <span style="color:gray;font-size: 0.8rem;"> (<a href="index.php?show_uid=<{$page.uid}>"><{$page.uid_name}></a> / <{$page.counter}> / <a href="index.php?ncsn=<{$page.ncsn}>"><{$page.cate_name}></a>)</span>
                            <{$page.content}>
                        </td>
                    </tr>
                <{/foreach}>
            </tbody>
        </table>
    </div>
    <{$bar}>
    <{includeq file="$xoops_rootpath/modules/tadnews/templates/sub_rss.tpl"}>
<{/if}>