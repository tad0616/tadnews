<div class="news_page_container">
    <div <{if $data.need_sign}>style="background-image: url('<{$data.need_sign}>'); background-position: right top; background-repeat: no-repeat;"<{/if}>>
        <h4 class="my">
            <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$data.nsn}>">
            <{$data.news_title}>
            </a>
        </h4>
    </div>
    <div class="news_page_content">
        <{if $data.not_news!=1 or ($data.not_news==1 and $cate_set_title==1)}>
            <div class="row news_page_info">
                <div class="col-md-6">
                    <{$data.prefix_tag}>

                    <span class="news_page_info_text">
                        <a href="<{$xoops_url}>/userinfo.php?uid=<{$data.uid}>"><{$data.uid_name}></a>
                        -
                        <a href="<{$xoops_url}>/modules/tadnews/<{$data.link_page}>?ncsn=<{$data.ncsn}>"><{$data.cate_name}></a>
                        |
                        <{$data.post_date}>
                        |
                        <{$smarty.const._TADNEWS_HOT}>
                        <{$data.counter}>
                    </span>
                    <{$data.star}>
                </div>
                <div class="col-md-6 text-right text-end"><{$data.fun}></div>
            </div>
        <{/if}>

        <div style="margin: 30px;">
            <{$data.pic}>
            <{$data.content}>
        </div>
        <div style="clear:both;"></div>
    </div>
</div>