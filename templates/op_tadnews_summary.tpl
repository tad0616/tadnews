<{$rating_js}>
<{if $page}>
    <{foreach from=$page item=page }>
        <{includeq file="$xoops_rootpath/modules/tadnews/templates/sub_summary.tpl"}>
    <{/foreach}>

    <div class="text-center;">
        <{$bar}>
    </div>

    <{includeq file="$xoops_rootpath/modules/tadnews/templates/sub_rss.tpl"}>
<{/if}>
