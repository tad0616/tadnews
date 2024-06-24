<{$block.rating_js}>
<{foreach from=$block.page item=page}>
    <{if $page.enable=="1" or $page.uid==$uid}>
        <{include file="$xoops_rootpath/modules/tadnews/templates/sub_summary.tpl"}>
    <{/if}>
<{/foreach}>
