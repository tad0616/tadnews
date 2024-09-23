<{$block.rating_js|default:''}>
<{if $block.page|default:false}>
    <{foreach from=$block.page item=data}>
        <{include file="$xoops_rootpath/modules/tadnews/templates/sub_summary.tpl"}>
    <{/foreach}>
<{/if}>
