<{foreach from=$all_news item=news_data}>
    <div class="row" style="margin-bottom: 40px;">
        <div class="col-md-2">
            <a href="<{$xoops_url}>/modules/tadnews/index.php?ncsn=<{$news_data.ncsn}>">
                <img src="<{$news_data.pic}>" alt="<{$news_data.nc_title}>" title="<{$news_data.nc_title}>" style="width: 100%;">
            </a>

            <h4 class="my"><a href="index.php?ncsn=<{$news_data.ncsn}>"><{$news_data.nc_title}></a></h4>
        </div>
        <div class="col-md-10">
            <{if $news_data.news|default:false}>
                <table class="table table-striped table-hover table-shadow">
                    <tr class="my">
                        <th><a href="index.php?ncsn=<{$news_data.ncsn}>"><{$news_data.nc_title}></a></th>
                    </tr>
                    <{foreach from=$news_data.news item=news}>
                        <tr>
                            <td>
                                <div class="pull-right float-right float-end"><{$news.files}></div>
                                <{$news.post_date}>
                                <{$news.prefix_tag}>
                                <{if $news.need_sign|default:false}>
                                    <img src="<{$news.need_sign}>" alt="<{$news.news_title}>" style="margin:3px;">
                                <{/if}>
                                <{$news.always_top_pic}>
                                <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$news.nsn}>"><{$news.news_title}></a>
                            </td>
                        </tr>
                    <{/foreach}>
                </table>
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

<{include file="$xoops_rootpath/modules/tadnews/templates/sub_rss.tpl"}>