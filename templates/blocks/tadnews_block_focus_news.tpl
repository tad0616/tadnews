<{$block.rating_js|default:''}>
<{foreach from=$block.page item=data}>
    <{if $data.enable=="1" or $data.uid==$uid}>
        <{include file="$xoops_rootpath/modules/tadnews/templates/sub_summary.tpl"}>
    <{/if}>
<{/foreach}>
