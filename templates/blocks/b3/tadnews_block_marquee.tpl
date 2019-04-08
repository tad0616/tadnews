<style>
  #news_marquee_<{$block.randStr}> {
    overflow: hidden;
    width: 100%;
    height:4em;
    line-height:1.8;
    border:1px solid #cfcfcf;
    background-color:#FCFCFC;
    box-shadow: 0px 1px 2px 1px #cfcfcf inset;
    <{$block.css}>
  }
  .news_marquee_item_<{$block.randStr}>{
    line-height:1.4;
    margin:5px;
    <{$block.item_css}>
  }
</style>
<script type="text/javascript">
  $(document).ready(function(){
    $('#news_marquee_<{$block.randStr}>').marquee({
      //speed in milliseconds of the marquee
      duration: <{$block.duration}>,
      //gap in pixels between the tickers
      gap: 0,
      //time in milliseconds before the marquee will start animating
      delayBeforeStart: 0,
      //'left' or 'right'
      direction: '<{$block.direction}>',
      //true or false - should the marquee be duplicated to show an effect of continues flow
      duplicated: true,
      pauseOnHover: true
    });
  });
</script>


<{if $block.page}>
  <div id="news_marquee_<{$block.randStr}>">
    <{if $block.direction=="left" or $block.direction=="right"}><div class="news_marquee_item_<{$block.randStr}>"><{/if}>
      <{foreach item=page from=$block.page}>
        <{if $block.direction=="up" or $block.direction=="down"}><div class="news_marquee_item_<{$block.randStr}>"><{else}><span style="margin-right:20px;"><{/if}>
          <{$page.post_date}>
          <{$page.pic}>
          <{$page.prefix_tag}>
          <{if $page.need_sign}>
            <img src="<{$page.need_sign}>" align="absmiddle" alt="<{$page.news_title}>" style="margin:3px;">
          <{/if}>
          <{$page.enable_txt}>
          <{$page.today_pic}>
          <a href="<{$xoops_url}>/modules/tadnews/<{$page.link_page}>?nsn=<{$page.nsn}>"><{$page.news_title}></a>
        <{if $block.direction=="up" or $block.direction=="down"}></div><{else}></span><{/if}>
      <{/foreach}>
    <{if $block.direction=="left" or $block.direction=="right"}></div><{/if}>
  </div>

<{/if}>
