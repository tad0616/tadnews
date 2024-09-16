<p>
  <{$toolbar}>
</p>

<{if $page|default:false}>
    <div class="panel panel-default card">
        <table class="table table-striped table-bordered">
            <tbody>
                <{foreach from=$page item=news}>
                    <tr>
                        <td>
                            <{$news.chkbox}>
                            <{$news.post_date}>
                            <{$news.prefix_tag}>
                            <{if $news.need_sign|default:false}>
                                <img src="<{$news.need_sign}>" alt="<{$news.news_title}>" style="margin:3px;">
                            <{/if}>
                            <{$news.enable_txt}>
                            <{$news.today_pic}>
                            <a href="<{$news.link_page}>?nsn=<{$news.nsn}>"><{$news.news_title}></a>
                            <span style="color:gray;font-size: 0.8rem;"> (<a href="index.php?show_uid=<{$news.uid}>"><{$news.uid_name}></a> / <{$news.counter}> / <a href="<{$news.link_page}>?ncsn=<{$news.ncsn}>"><{$news.cate_name}></a>)</span> <{$news.content}>
                        </td>
                        <td>
                            <a href="javascript:delete_tad_news_func(<{$news.nsn}>);" class='btn btn-danger btn-sm btn-xs'><i class="fa fa-times"></i> <{$smarty.const._TAD_DEL}></a>
                            <a href="post.php?op=tad_news_form&nsn=<{$news.nsn}>" class="btn btn-warning btn-sm btn-xs"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}></a>
                            <{if $news.enable!='1'}>
                            <a href="post.php?op=enable_news&nsn=<{$news.nsn}>" class="btn btn-success btn-sm btn-xs"><{$smarty.const._MD_TADNEWS_NEWS_ENABLE_OK}></a>
                            <{/if}>
                        </td>
                    </tr>
                <{/foreach}>
            </tbody>
        </table>
    </div>
    <{$bar}>

<{/if}>