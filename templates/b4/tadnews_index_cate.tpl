<!--TadNews Start-->
<{if $ncsn}>
  <link rel="alternate" type="application/rss+xml" title="tadnews rss" href="<{$xoops_url}>/modules/tadnews/rss.php?ncsn=<{$ncsn}>">
<{/if}>

<link rel="stylesheet" type="text/css" media="screen" href="<{$xoops_url}>/modules/tadtools/css/iconize.css">

<p>
  <{$toolbar}>
</p>

<h1 class="sr-only" style="display: none;">All News</h1>

<{foreach item=all_news from=$all_news}>

  <div class="row" style="margin: 40px 0px 0px;">
    <div class="col-sm-12">
      <a href="index.php?ncsn=<{$all_news.ncsn}>" style="font-size: 1.875em;"><{$all_news.nc_title}></a>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-2">
      <a href="<{$xoops_url}>/modules/tadnews/index.php?ncsn=<{$all_news.ncsn}>">
        <img src="<{$all_news.pic}>" alt="<{$all_news.nc_title}>" title="<{$all_news.nc_title}>" style="width: 100%;">
      </a>
    </div>
    <div class="col-sm-10">
      <{if $all_news.news}>
        <table class="table table-striped table-bordered">
          <{foreach  item=news from=$all_news.news}>
            <tr>
              <td>
                <div class="pull-right"><{$news.files}></div>
                <{$news.post_date}>
                <{$news.prefix_tag}>
                <{if $news.need_sign}>
                  <img src="<{$news.need_sign}>" align="absmiddle" alt="<{$news.news_title}>" style="margin:3px;">
                <{/if}>
                <{$news.always_top_pic}>
                <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$news.nsn}>"><{$news.news_title}></a>
              </td>
            </tr>
          <{/foreach}>
        </table>

      <{else}>
        <div class="row">
          <div class="col-sm-12" style="margin: 2px 0px; padding:2px;">
            <div style="font-size: 1.875em; color: #cfcfcf; padding-top: 30px;">
              <{$smarty.const._TADNEWS_EMPTY}>
            </div>
          </div>
        </div>
      <{/if}>
    </div>
  </div>
<{/foreach}>


<{includeq file="$xoops_rootpath/modules/tadnews/templates/rss.tpl"}>