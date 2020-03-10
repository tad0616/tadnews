<{$block.rating_js}>
<{$block.del_js}>
<{foreach item=page from=$block.page}>
  <{if $page.enable=="1" or $page.uid==$uid}>
  <div class="row" style="border-bottom: 1px dotted gray;">
    <div class="col-sm-12">
      <div <{if $page.need_sign}>style="background-image: url('<{$page.need_sign}>'); background-position: right top; background-repeat: no-repeat;"<{/if}>>
        <h2 style="padding:10px 0px;">
          <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$page.nsn}>" style="font-size: 1.5em;  font-weight: normal;">
          <{$page.news_title}>
          </a>
        </h2>
      </div>

      <{$page.prefix_tag}>

      <span style="font-size: 0.6875em;">
        <a href="<{$xoops_url}>/userinfo.php?uid=<{$page.uid}>"><{$page.uid_name}></a> - <a href="<{$xoops_url}>/modules/tadnews/<{$page.link_page}>?ncsn=<{$page.ncsn}>"><{$page.cate_name}></a> | <{$page.post_date}> | <{$smarty.const._TADNEWS_HOT}><{$page.counter}>
      </span>


      <div style="border:1px solid #efefef; background-color: #fefefe; padding: 30px; margin:10px auto; line-height: 2em; font-weight: normal; <{$block.summary_css}>">
        <{$page.pic}>
        <{$page.content}>
        <div style="clear:both;"></div>
        <div class="text-right">
          <{$page.fun}>
        </div>
      </div>

    </div>
  </div>
  <{/if}>
<{/foreach}>
