<p>
  <{$toolbar}>
</p>

<{if $page}>
    <div class="panel panel-default card">
        <table class="table table-striped table-bordered">
            <tbody>
                <{foreach item=page from=$page}>
                    <tr>
                        <td>
                            <{$page.chkbox}>
                            <{$page.post_date}>
                            <{$page.prefix_tag}>
                            <{if $page.need_sign}>
                                <img src="<{$page.need_sign}>" align="absmiddle" alt="<{$page.news_title}>" style="margin:3px;">
                            <{/if}>
                            <{$page.enable_txt}>
                            <{$page.today_pic}>
                            <a href="<{$page.link_page}>?nsn=<{$page.nsn}>"><{$page.news_title}></a>
                            <span style="color:gray;font-size: 0.8em;"> (<a href="index.php?show_uid=<{$page.uid}>"><{$page.uid_name}></a> / <{$page.counter}> / <a href="<{$page.link_page}>?ncsn=<{$page.ncsn}>"><{$page.cate_name}></a>)</span> <{$page.content}>
                        </td>
                        <td>
                            <a href="javascript:delete_tad_news_func(<{$page.nsn}>);" class='btn btn-danger btn-sm btn-xs'><i class="fa fa-times"></i> <{$smarty.const._TAD_DEL}></a>
                            <a href="post.php?op=tad_news_form&nsn=<{$page.nsn}>" class="btn btn-warning btn-sm btn-xs"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}></a>
                            <{if $page.enable!='1'}>
                            <a href="post.php?op=enable_news&nsn=<{$page.nsn}>" class="btn btn-success btn-sm btn-xs"><{$smarty.const._MD_TADNEWS_NEWS_ENABLE_OK}></a>
                            <{/if}>
                        </td>
                    </tr>
                <{/foreach}>
            </tbody>
        </table>
    </div>
    <{$bar}>

<{/if}>