<{if $block.page}>
  <{foreach item=page from=$block.page}>
    <div>
      <a href="<{$xoops_url}>/modules/tadnews/newspaper.php?op=preview&npsn=<{$page.npsn}>" target="_blank"><{$page.title}></a>
    </div>
  <{/foreach}>
<{else}>
  <{$smarty.const._MB_TADNEWS_NO_NEWSPAPER}>
<{/if}>