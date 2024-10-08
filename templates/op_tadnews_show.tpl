<{$rating_js|default:''}>

<{if $page|default:false}>
    <{foreach from=$page item=news}>
        <{if $news.enable=="1" or $news.uid==$uid}>
            <div class="news_page_container">
                <div <{if $news.need_sign|default:false}>style="background-image: url('<{$news.need_sign}>'); background-position: right top; background-repeat: no-repeat;"<{/if}>>
                    <h3 class="my">
                        <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$news.nsn}>">
                        <{$news.news_title}>
                        </a>
                    </h3>
                </div>

                <div class="news_page_content">
                    <div class="row news_page_info">
                        <div class="col-md-6">
                            <{$news.prefix_tag}>

                            <span class="news_page_info_text">
                                <a href="<{$xoops_url}>/userinfo.php?uid=<{$news.uid}>"><{$news.uid_name}></a>
                                -
                                <a href="<{$xoops_url}>/modules/tadnews/<{$news.link_page}>?ncsn=<{$news.ncsn}>"><{$news.cate_name}></a>
                                |
                                <{$news.post_date}>
                                |
                                <{$smarty.const._TADNEWS_HOT}>
                                <{$news.counter}>
                            </span>
                            <{$news.star}>
                        </div>
                        <div class="col-md-6 text-right text-end"><{$news.fun}></div>
                    </div>
                    <div style="margin: 30px;">
                        <{$news.pic}>
                        <{$news.content}>
                    </div>

                    <div style="clear:both;"></div>
                </div>

                <{if $news.files|default:false}>
                    <div style="margin: 30px 0px;">
                        <{$news.files}>
                    </div>
                <{/if}>

                <{if $news.have_read_chk|default:false}>
                    <div class="text-center" style="margin: 30px 0px;">
                        <{$news.have_read_chk}>
                    </div>
                <{/if}>


                <{if $news.push|default:false}>
                    <div class="text-right text-end" style="margin: 30px 0px;">
                        <{$news.push}>
                    </div>
                <{/if}>
            </div>
        <{else}>
            <h2 class="sr-only visually-hidden"><{$smarty.const._MD_TADNEWS_HIDDEN}></h2>
            <div class="alert alert-danger">
                <p><{$smarty.const._MD_TADNEWS_HIDDEN}></p>
            </div>
        <{/if}>

        <{if $show_next_btn|default:false}>
            <div class="row" style="margin-bottom: 30px;">
                <div class="col-md-6 d-grid gap-2">
                    <{if $news.back_news_link|default:false}>
                        <a href="<{$news.back_news_link}>" class="btn btn-default btn-outline-info btn-block">
                        <img src="images/left.png" hspace=2 alt="Previous">
                        <{$news.back_news_title}>
                        </a>
                    <{/if}>
                </div>
                <div class="col-md-6 d-grid gap-2">
                    <{if $news.next_news_link|default:false}>
                        <a href="<{$news.next_news_link}>" class="btn btn-default btn-outline-info btn-block"><{$news.next_news_title}><img src="images/right.png" hspace=2 alt="Next"></a>
                    <{/if}>
                </div>
            </div>
        <{/if}>
    <{/foreach}>
<{else}>
    <h2 class="sr-only visually-hidden"><{$smarty.const._MD_TADNEWS_HIDDEN}></h2>
    <div class="alert alert-danger">
        <p><{$smarty.const._MD_TADNEWS_HIDDEN}></p>
    </div>
<{/if}>