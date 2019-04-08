<{foreach item=all_news from=$block.all_news}>
  <{if $all_news.news}>
    <div class="row">
      <{if $all_news.show_pic}>
        <div class="col-sm-2">
          <a href="<{$xoops_url}>/modules/tadnews/index.php?ncsn=<{$all_news.ncsn}>">
            <img src="<{$all_news.pic}>" alt="<{$all_news.nc_title}>" title="<{$all_news.nc_title}>" class="img-responsive thumbnail">
          </a>
        </div>
        <div class="col-sm-10">
      <{else}>
        <div class="col-sm-12">
      <{/if}>
      <h4><a href="<{$xoops_url}>/modules/tadnews/index.php?ncsn=<{$all_news.ncsn}>"><{$all_news.nc_title}></a></h4>
      <ul>
      <{foreach from=$all_news.news item=news}>
        <li>
          <{$news.post_date}>
          <{$news.always_top_pic}>
          <{$news.prefix_tag}>
          <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$news.nsn}>"><{$news.news_title}>
          </a>
          <{if $news.content}>
            <{$news.content}>
          <{/if}>
        </li>
      <{/foreach}>
      </ul>
      </div>
    </div>
  <{/if}>
<{/foreach}>
