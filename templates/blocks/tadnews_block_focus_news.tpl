<{$block.rating_js}>
<{$block.del_js}>
<{foreach item=page from=$block.page}>
    <{if $page.enable=="1" or $page.uid==$uid}>
        <div style="border-bottom: 1px dotted gray;">
            <{if $page.not_news!=1 or ($page.not_news==1 and $cate_set_title==1)}>
                <div <{if $page.need_sign}>style="background-image: url('<{$page.need_sign}>'); background-position: right top; background-repeat: no-repeat;"<{/if}>>
                    <h2 style="padding:10px 0px;">
                    <a href="<{$xoops_url}>/modules/tadnews/<{$page.link_page}>?nsn=<{$page.nsn}>" style="font-size: 1.1em;  font-weight: normal;">
                    <{$page.news_title}>
                    </a>
                    </h2>
                </div>

                <{$page.prefix_tag}>

                <span style="font-size: 0.8em;">
                    <a href="<{$xoops_url}>/userinfo.php?uid=<{$page.uid}>"><{$page.uid_name}></a> - <a href="<{$xoops_url}>/modules/tadnews/<{$page.link_page}>?ncsn=<{$page.ncsn}>"><{$page.cate_name}></a> | <{$page.post_date}> | <{$smarty.const._TADNEWS_HOT}><{$page.counter}>
                </span>
            <{/if}>


            <div style="border:1px solid #efefef; background-color: #fefefe; padding: 30px; margin:10px auto; line-height: 2em; font-weight: normal; <{$block.summary_css}>">
                <{$page.pic}>
                <{$page.content}>
                <div style="clear:both;"></div>
                <div class="text-right">
                <{$page.fun}>
                </div>
            </div>

        </div>
    <{/if}>
<{/foreach}>
