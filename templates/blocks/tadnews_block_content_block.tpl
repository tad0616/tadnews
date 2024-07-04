<{$block.rating_js}>
<{if $block.page}>
    <{foreach from=$block.page item=data}>
        <{include file="$xoops_rootpath/modules/tadnews/templates/sub_summary.tpl"}>
    <{/foreach}>
<{/if}>
