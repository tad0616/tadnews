<{if $block}>
    <div <{if $block.panel!=''}>class="panel panel-<{$block.panel}>"<{/if}>>
      <{if $block.show_title!='0'}>
        <div <{if $block.panel!=''}>class="panel-heading"<{/if}> style="font-size: 1.5em;"><a href="<{$xoops_url}>/modules/tadnews/page.php?ncsn=<{$block.ncsn}>" <{if $block.panel=='primary'}>style="color: white;"<{/if}>><{$block.nc_title}></a></div>
      <{/if}>
      <ul <{if $block.panel!=''}>class="list-group"<{/if}>>
      <{foreach from=$block.pages item=page}>
        <li <{if $block.panel!=''}>class="list-group-item" style="padding-left: <{$page.padding}>em;"<{else}>style="margin-left: <{$page.padding}>em;"<{/if}>>
            <a href="<{$page.url}>"><{$page.title}></a>
            <{if $page.type=="cate"}>
                <i class="fa fa-caret-down" aria-hidden="true"></i>
            <{/if}>
        </li>
      <{/foreach}>
      </ul>
    </div>
<{/if}>