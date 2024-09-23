<{if $block.page|default:false}>
    <div class="panel panel-default card">
        <table class="table table-striped">
        <tr>
            <th nowrap><{$smarty.const._MB_TADNEWS_NEWS_CATE}></th>
            <th><{$smarty.const._MB_TADNEWS_NEWS_TITLE}></th>
            <th nowrap><{$smarty.const._MB_TADNEWS_COUNTER}></th>
        </tr>
            <{foreach item=page from=$block.page}>
                <tr>
                    <td><a href="<{$xoops_url}>/modules/tadnews/<{$page.link_page}>?ncsn=<{$page.ncsn}>"><{$page.cate_name}></a></td>
                    <td>
                    <{$page.prefix_tag}>
                    <{if $page.need_sign|default:false}>
                    <img src="<{$page.need_sign}>" alt="<{$page.news_title}>" style="margin:3px;">
                <{/if}>
                <a href="<{$xoops_url}>/modules/tadnews/<{$page.link_page}>?nsn=<{$page.nsn}>"><{$page.news_title}></a></td>
                    <td><{$page.counter}></td>
                </tr>
            <{/foreach}>
        </table>
    </div>
<{/if}>