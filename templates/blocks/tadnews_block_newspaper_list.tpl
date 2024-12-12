<{if $block.page|default:false}>
    <ul class="vertical_menu">
        <{foreach item=page from=$block.page}>
            <li>
                <a href="<{$xoops_url}>/modules/tadnews/newspaper.php?op=preview&npsn=<{$page.npsn}>" target="_blank">
                    <i class="fa fa-newspaper"></i>
                    <{$page.title}>
                </a>
            </li>
        <{/foreach}>
    </ul>
<{else}>
    <{$smarty.const._MB_TADNEWS_NO_NEWSPAPER}>
<{/if}>