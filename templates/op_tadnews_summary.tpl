<{$rating_js}>
<h2><{$smarty.const._MD_TADNEWS_LIST}></h2>
<{if $page}>
    <{foreach from=$page item=page }>
        <{include file="$xoops_rootpath/modules/tadnews/templates/sub_summary.tpl"}>
    <{/foreach}>

    <div class="text-center;">
        <{$bar}>
    </div>

    <{include file="$xoops_rootpath/modules/tadnews/templates/sub_rss.tpl"}>
<{/if}>
