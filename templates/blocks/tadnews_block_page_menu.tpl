<{if $block}>
    <{if $block.show_title!='0'}>
        <div class="text-center" style="background-color:<{$block.bgcolor}>;<{$block.bg_css}>"><a href="<{$xoops_url}>/modules/tadnews/page.php?ncsn=<{$block.ncsn}>" style="color: <{$block.color}>;<{$block.text_css}>"><{$block.nc_title}></a></div>
    <{/if}>
    <ul class="vertical_menu">
        <{foreach from=$block.pages item=page}>
            <li style="padding-left: <{$page.padding}>em;<{if $block.now_nsn==$page.nsn}>background: #efefef;<{/if}>">
                <a href="<{$page.url}>">
                    <{$page.title}>
                    <{if $page.type=="cate"}>
                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                    <{/if}>
                </a>
            </li>
        <{/foreach}>
    </ul>
<{/if}>