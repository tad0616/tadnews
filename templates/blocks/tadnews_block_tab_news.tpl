<style type="text/css" media="screen">

    #<{$block.tab_news_name}>>ul.resp-tabs-list > li{
        font-size: <{$block.tab_font_size}>em;
    }

</style>

<div id="<{$block.tab_news_name}>">
    <ul class="resp-tabs-list vert">
        <{if $block.latest_news}>
            <li><{$smarty.const._MB_TADNEWS_LATEST_NEWS_TAB}></li>
        <{/if}>
        <{foreach item=all_news from=$block.all_news}>
            <li><{$all_news.nc_title}></li>
        <{/foreach}>
    </ul>

    <div class="resp-tabs-container vert">
        <{if $block.latest_news}>
            <div>
                <{foreach from=$block.latest_news item=news}>
                    <div style="padding: 8px;">
                        <{$news.post_date}> <{$news.always_top_pic}><{$news.today_pic}> <{$news.prefix_tag}>
                        <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$news.nsn}>"><{$news.news_title}></a>
                    </div>
                <{/foreach}>
                <div class="text-right">
                    [ <a href="<{$xoops_url}>/modules/tadnews/index.php">more...</a> ]
                </div>
            </div>
        <{/if}>
        <{foreach item=all_news from=$block.all_news}>
            <div>
                <{foreach from=$all_news.news item=news}>
                    <div style="padding: 8px;">
                        <{$news.post_date}> <{$news.always_top_pic}><{$news.today_pic}> <{$news.prefix_tag}>
                        <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$news.nsn}>"><{$news.news_title}></a>
                    </div>
                <{/foreach}>
                <div class="text-right">
                    [ <a href="<{$xoops_url}>/modules/tadnews/index.php?ncsn=<{$all_news.ncsn}>">more...</a> ]
                </div>
            </div>
        <{/foreach}>
    </div>
</div>

<div style="clear: both;"></div>