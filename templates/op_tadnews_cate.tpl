<!--TadNews Start-->
<h1 class="sr-only" style="display: none;">All News</h1>

<{foreach item=all_news from=$all_news}>
    <div class="row" style="margin-bottom: 40px;">
        <div class="col-sm-2">
            <a href="<{$xoops_url}>/modules/tadnews/index.php?ncsn=<{$all_news.ncsn}>">
                <img src="<{$all_news.pic}>" alt="<{$all_news.nc_title}>" title="<{$all_news.nc_title}>" style="width: 100%;">
            </a>

            <a href="index.php?ncsn=<{$all_news.ncsn}>" style="font-size: 1.25rem;"><{$all_news.nc_title}></a>
        </div>
        <div class="col-sm-10">

            <{if $all_news.news}>
                <table class="table table-striped table-bordered">
                    <{foreach  item=news from=$all_news.news}>
                        <tr>
                        <td>
                            <div class="pull-right"><{$news.files}></div>
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

            <{else}>
                <div class="row" style="margin: 2px 0px; padding:2px;">
                    <div class="col-sm-12">
                        <div style="font-size: 1.875rem; color: #cfcfcf; padding-top: 30px;">
                        <{$smarty.const._TADNEWS_EMPTY}>
                        </div>
                    </div>
                </div>
            <{/if}>
            </div>
        </div>

<{/foreach}>

<{includeq file="$xoops_rootpath/modules/tadnews/templates/sub_rss.tpl"}>