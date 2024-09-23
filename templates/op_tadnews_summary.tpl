<{$rating_js|default:''}>
<h2><{$smarty.const._MD_TADNEWS_LIST}></h2>
<{if $page|default:false}>
    <{foreach from=$page item=data }>
        <{include file="$xoops_rootpath/modules/tadnews/templates/sub_summary.tpl"}>
    <{/foreach}>

    <div class="text-center;">
        <{$bar|default:''}>
    </div>

    <{include file="$xoops_rootpath/modules/tadnews/templates/sub_rss.tpl"}>
<{/if}>
