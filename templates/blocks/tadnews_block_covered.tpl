<{if $block.page}>

  <{assign var="i" value=0}>
  <{assign var="total" value=1}>
  <{foreach from=$block.page item=news}>
    <{if $i==0}>
    <div class="row">
    <{/if}>
    <div class="col-sm-<{$block.num}>" style="margin-bottom:16px;">
      <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$news.nsn}>">
        <div style="height:150px;overflow:hidden;border:1px solid gray;vertical-align:middle;background-image: url('<{if $news.image_big}><{$news.image_big}><{else}><{$xoops_url}>/modules/tadnews/images/demo<{$total}>.jpg<{/if}>'); background-size: cover;">
        </div>
      </a>
      <div style="font-size: 1.125em; overflow:hidden;height:15pt;line-height:16pt;margin:8px 3px; "><a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$news.nsn}>"><{$news.news_title}></a></div>
      <div style="<{$block.summary_css}>">
        <{$news.content}>
      </div>
    </div>

    <{assign var="i" value=$i+1}>
    <{if $i == $block.cols || $total==$block.count}>
      </div>
      <{assign var="i" value=0}>
    <{/if}>
    <{assign var="total" value=$total+1}>
  <{/foreach}>
<{/if}>