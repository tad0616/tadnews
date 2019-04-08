<!--TadNews Start-->
<{if $ncsn}>
  <link rel="alternate" type="application/rss+xml" title="tadnews rss" href="<{$xoops_url}>/modules/tadnews/rss.php?ncsn=<{$ncsn}>" />
<{/if}>
<link rel="stylesheet" type="text/css" media="screen" href="<{$xoops_url}>/modules/tadtools/css/iconize.css" />

<p>
  <{$toolbar}>
</p>

<!--TadNews Start-->
<{foreach item=all_news from=$all_news}>

  <h3>
  <{if $isAdmin}>
    <div class="pull-right">
      <{if $show_add_to_menu=='1'}>
        <a href="page.php?op=add_to_menu&ncsn=<{$all_news.ncsn}>" class="btn btn-success"><{$smarty.const._MD_TADNEWS_ADD_TO_MENU}></a>
      <{/if}>
      <a href="admin/page.php?op=modify_news_cate&ncsn=<{$all_news.ncsn}>" class="btn btn-warning"><{$smarty.const._MD_TADNEWS_EDIT_CATE}></a>
      <a href="post.php?ncsn=<{$all_news.ncsn}>" class="btn btn-primary"><{$smarty.const._MD_TADNEWS_POST}></a>
    </div>
  <{/if}>
  <a href="page.php?ncsn=<{$all_news.ncsn}>" style="text-shadow:1px 1px 1px #aaaaaa;"><{$all_news.nc_title}></a>
  </h3>

  <div class="list-group">
  <{foreach  item=news from=$all_news.news}>
    <a href="<{$xoops_url}>/modules/tadnews/page.php?nsn=<{$news.nsn}>" class="list-group-item">
      <span class="badge"><{$news.counter}></span>
      <{$news.news_title}>
    </a>
  <{/foreach}>
  </div>


  <{if $show_add_to_menu!='1' and $isAdmin}>
    <div class="alter alert-warning"><{$smarty.const._MD_TADNEWS_ADD_TO_MENU_ALERT}></div>
  <{/if}>

<{/foreach}>
