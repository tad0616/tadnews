<{foreach item=all_news from=$all_news}>
    <div class="row" style="margin-bottom: 40px;">
        <div class="col-md-2">
            <a href="<{$xoops_url}>/modules/tadnews/index.php?ncsn=<{$all_news.ncsn}>">
                <img src="<{$all_news.pic}>" alt="<{$all_news.nc_title}>" title="<{$all_news.nc_title}>" style="width: 100%;">
            </a>

            <h4 class="my"><a href="index.php?ncsn=<{$all_news.ncsn}>"><{$all_news.nc_title}></a></h4>
        </div>
        <div class="col-md-10">
            <{if $all_news.news}>

                <div class="panel panel-default card">
                    <table class="table table-striped table-bordered">
                        <{foreach  item=news from=$all_news.news}>
                            <tr>
                                <td>
                                    <div class="pull-right float-right"><{$news.files}></div>
                                    <{$news.post_date}>
                                    <{$news.prefix_tag}>
                                    <{if $news.need_sign}>
                                        <img src="<{$news.need_sign}>" align="absmiddle" alt="<{$news.news_title}>" style="margin:3px;">
                                    <{/if}>
                                    <{$news.always_top_pic}>
                                    <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$news.nsn}>"><{$news.news_title}></a>
                                </td>
                            </tr>
                        <{/foreach}>
                    </table>
                </div>

            <{else}>
            <div class="alert alert-warning">
                <div style="font-size: 1.875rem; color: #cfcfcf; padding: 30px;">
                    <{$smarty.const._TADNEWS_EMPTY}>
                </div>
            </div>
            <{/if}>
            </div>
        </div>

<{/foreach}>

<{includeq file="$xoops_rootpath/modules/tadnews/templates/sub_rss.tpl"}>