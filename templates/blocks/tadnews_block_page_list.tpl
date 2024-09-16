<{if $block|default:false}>
    <{if $block.show_title!='0'}>
        <div class="text-center" style="padding: 4px;background-color:<{$block.bgcolor}>;<{$block.bg_css}>"><a href="<{$xoops_url}>/modules/tadnews/page.php?ncsn=<{$block.ncsn}>" style="font-size: 1.1rem; color: <{$block.color}>;<{$block.text_css}>"><{$block.nc_title}></a></div>
    <{/if}>
    <ul class="vertical_menu">
        <{foreach from=$block.pages item=page}>
            <li style="padding-left: <{$page.padding}>rem;">
                <a href="<{$page.url}>">
                    <{$page.title}>
                    <{if $page.type=="cate"}>
                        <i class="fa fa-caret-down"></i>
                    <{/if}>
                </a>
            </li>
        <{/foreach}>
    </ul>
<{/if}>