<{$rating_js}>

<{if $page}>
    <{foreach item=page from=$page}>
        <{if $page.enable=="1" or $page.uid==$uid}>
            <div class="news_page_container">
                <div <{if $page.need_sign}>style="background-image: url('<{$page.need_sign}>'); background-position: right top; background-repeat: no-repeat;"<{/if}>>
                    <h3 class="my">
                        <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$page.nsn}>">
                        <{$page.news_title}>
                        </a>
                    </h3>
                </div>

                <div class="news_page_content">
                    <div class="row news_page_info">
                        <div class="col-md-6">
                            <{$page.prefix_tag}>

                            <span class="news_page_info_text">
                                <a href="<{$xoops_url}>/userinfo.php?uid=<{$page.uid}>"><{$page.uid_name}></a>
                                -
                                <a href="<{$xoops_url}>/modules/tadnews/<{$page.link_page}>?ncsn=<{$page.ncsn}>"><{$page.cate_name}></a>
                                |
                                <{$page.post_date}>
                                |
                                <{$smarty.const._TADNEWS_HOT}>
                                <{$page.counter}>
                            </span>
                            <{$page.star}>
                        </div>
                        <div class="col-md-6 text-right text-end"><{$page.fun}></div>
                    </div>
                    <div style="margin: 30px;">
                        <{$page.pic}>
                        <{$page.content}>
                    </div>

                    <div style="clear:both;"></div>
                </div>

                <{if $page.files}>
                    <div style="margin: 30px 0px;">
                        <{$page.files}>
                    </div>
                <{/if}>

                <{if $page.have_read_chk}>
                    <div class="text-center" style="margin: 30px 0px;">
                        <{$page.have_read_chk}>
                    </div>
                <{/if}>


                <{if $page.push}>
                    <div class="text-right text-end" style="margin: 30px 0px;">
                        <{$page.push}>
                    </div>
                <{/if}>

                <{$page.facebook_comments}>

                <div style="text-align: center; padding: 3px; margin: 3px;">
                    <{$commentsnav}>
                    <{$lang_notice}>
                </div>

                <div style="margin: 3px; padding: 3px;">
                    <{if $comment_mode == "flat"}>
                        <{include file="db:system_comments_flat.html"}>
                    <{elseif $comment_mode == "thread"}>
                        <{include file="db:system_comments_thread.html"}>
                    <{elseif $comment_mode == "nest"}>
                        <{include file="db:system_comments_nest.html"}>
                    <{/if}>
                </div>
            </div>
        <{else}>
            <h2 class="sr-only visually-hidden"><{$smarty.const._MD_TADNEWS_HIDDEN}></h2>
            <div class="alert alert-danger">
                <p><{$smarty.const._MD_TADNEWS_HIDDEN}></p>
            </div>
        <{/if}>

        <{if $show_next_btn}>
            <div class="row" style="margin-bottom: 30px;">
                <div class="col-md-6 d-grid gap-2">
                    <{if $page.back_news_link}>
                        <a href="<{$page.back_news_link}>" class="btn btn-default btn-outline-info btn-block">
                        <img src="images/left.png" hspace=2 alt="Previous">
                        <{$page.back_news_title}>
                        </a>
                    <{/if}>
                </div>
                <div class="col-md-6 d-grid gap-2">
                    <{if $page.next_news_link}>
                        <a href="<{$page.next_news_link}>" class="btn btn-default btn-outline-info btn-block"><{$page.next_news_title}><img src="images/right.png" hspace=2 alt="Next"></a>
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