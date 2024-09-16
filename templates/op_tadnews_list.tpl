<h2 class="sr-only visually-hidden"><{$smarty.const._MD_TADNEWS_LIST}></h2>
<{if $page|default:false}>
    <table class="table table-striped table-hover table-shadow">
        <tbody>
            <{if $cate.nc_title|default:false}>
                <tr class="my">
                    <th><{$cate.nc_title}></th>
                </tr>
            <{/if}>
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
                        <a href="<{$xoops_url}>/modules/tadnews/<{$news.link_page}>?nsn=<{$news.nsn}>"><{$news.news_title}></a>
                        <span style="color:gray;font-size: 0.8rem;"> (<a href="index.php?show_uid=<{$news.uid}>"><{$news.uid_name}></a> / <{$news.counter}> / <a href="index.php?ncsn=<{$news.ncsn}>"><{$news.cate_name}></a>)</span>
                        <{$news.content}>
                    </td>
                </tr>
            <{/foreach}>
        </tbody>
    </table>
    <{$bar}>
    <{include file="$xoops_rootpath/modules/tadnews/templates/sub_rss.tpl"}>
<{/if}>