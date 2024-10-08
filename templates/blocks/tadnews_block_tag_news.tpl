<style type="text/css" media="screen">

    #<{$block.tag_news_name}>>ul.resp-tabs-list > li{
        font-size: <{$block.tab_font_size}>rem;
    }
</style>

<div id="<{$block.tag_news_name}>">
    <ul class="resp-tabs-list vert">
        <{if $block.latest_news|default:false}>
            <li><{$smarty.const._MB_TADNEWS_LATEST_NEWS_TAB}></li>
        <{/if}>
        <{foreach from=$block.tags key=tag_sn item=tag}>
            <li><{$tag|default:''}></li>
        <{/foreach}>
    </ul>

    <div class="resp-tabs-container vert">
        <{if $block.latest_news|default:false}>
            <div>
                <{foreach from=$block.latest_news item=news}>
                    <div style="padding: 8px;">
                        <{$news.post_date|default:''}> <{$news.always_top_pic|default:''}><{$news.today_pic|default:''}> <{$news.prefix_tag|default:''}>
                        <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$news.nsn}>"><{$news.news_title}></a>
                    </div>
                <{/foreach}>
                <div class="text-right text-end">
                    [ <a href="<{$xoops_url}>/modules/tadnews/index.php">more...</a> ]
                </div>
            </div>
        <{/if}>
        <{foreach from=$block.tags key=tag_sn item=tag}>
            <div>
                <{if $block.all_news.$tag_sn.page}>
                    <{foreach from=$block.all_news.$tag_sn.page item=news}>
                        <div style="padding: 8px;">
                            <{$news.post_date|default:''}> <{$news.always_top_pic|default:''}><{$news.today_pic|default:''}>
                            <a href="<{$xoops_url}>/modules/tadnews/index.php?ncsn=<{$news.ncsn}>"><{$news.cate_name}></a> /
                            <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$news.nsn}>"><{$news.news_title}></a>
                        </div>
                    <{/foreach}>
                    <div class="text-right text-end">
                        [ <a href="<{$xoops_url}>/modules/tadnews/index.php?tag_sn=<{$tag_sn|default:''}>">more...</a> ]
                    </div>
                <{/if}>
            </div>
        <{/foreach}>
    </div>
</div>
<div style="clear: both;"></div>