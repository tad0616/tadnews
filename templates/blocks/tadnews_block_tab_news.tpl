<style type="text/css" media="screen">
  .resp-vtabs .resp-tabs-container{
    min-height: <{$block.min_height}>px;
  }
</style>

<div id="<{$block.tab_news_name}>">
  <ul class="resp-tabs-list vert">
    <{foreach item=all_news from=$block.all_news}>
      <{if $all_news.news}>
        <li><{$all_news.nc_title}></li>
      <{/if}>
    <{/foreach}>
  </ul>

  <div class="resp-tabs-container vert">
    <{foreach item=all_news from=$block.all_news}>
      <div>
      <{if $all_news.news}>
        <{foreach from=$all_news.news item=news}>
          <div style="padding: 8px;">
            <{$news.post_date}> <{$news.always_top_pic}> <{$news.prefix_tag}>
            <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$news.nsn}>"><{$news.news_title}></a>
          </div>
        <{/foreach}>
      <{/if}>
      </div>
    <{/foreach}>
  </div>
</div>
<div style="clear: both;"></div>