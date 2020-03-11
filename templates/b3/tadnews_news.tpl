<{$toolbar}>

<{$rating_js}>
<{$del_js}>

<{$jquery}>

<{if $page}>
    <{foreach item=page from=$page}>
        <{if $page.enable=="1" or $page.uid==$uid}>

            <div <{if $page.need_sign}>style="background-image: url('<{$page.need_sign}>'); background-position: right top; background-repeat: no-repeat;"<{/if}>>
                <h2 style="padding:10px 0px;">
                <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$page.nsn}>" style="font-size: 1.1em;  font-weight: normal; line-height: 150%;">
                <{$page.news_title}>
                </a>
                </h2>
            </div>


            <div class="row">
                <div class="col-sm-9">
                    <{$page.prefix_tag}>
                    <span style="font-size: 0.8em;">
                        <a href="<{$xoops_url}>/userinfo.php?uid=<{$page.uid}>"><{$page.uid_name}></a> - <a href="<{$xoops_url}>/modules/tadnews/<{$page.link_page}>?ncsn=<{$page.ncsn}>"><{$page.cate_name}></a> | <{$page.post_date}> | <{$smarty.const._TADNEWS_HOT}><{$page.counter}>

                    </span>
                </div>

                <div class="col-sm-3">
                    <div class="pull-right"><{$page.star}></div>
                </div>
            </div>

            <div style="border:1px solid #efefef; background-color: #fefefe; padding: 30px; margin:10px auto; line-height: 2em; font-weight: normal; <{$block.summary_css}>">
                <{$page.pic}>
                <{$page.content}>


                <div style="clear:both;"></div>
                <div class="text-right">
                    <{$page.fun}>
                </div>
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

            <div class="row">
                <div class="col-sm-6">
                    <{if $page.back_news_link}>
                        <a href="<{$page.back_news_link}>" class="btn btn-default btn-block"><img src="images/left.png" hspace=2 align="absmiddle" alt="<{$page.back_news_title}>" title="<{$page.back_news_title}>"><{$page.back_news_title}></a>
                    <{/if}>
                </div>
                <div class="col-sm-6">
                    <{if $page.next_news_link}>
                        <a href="<{$page.next_news_link}>" class="btn btn-default btn-block"><{$page.next_news_title}><img src="images/right.png" hspace=2 align="absmiddle" alt="<{$page.next_news_title}>" title="<{$page.next_news_title}>"></a>
                    <{/if}>
                </div>
            </div>


            <{if $page.push}>
                <div class="text-right" style="margin: 30px 0px;">
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

        <{else}>
            <div class="alert alert-danger">
                <p><{$smarty.const._MD_TADNEWS_HIDDEN}></p>
            </div>

            <div class="row">
                <div class="col-sm-6 text-left"><{$page.back_news}></div>
                <div class="col-sm-6 text-right"><{$page.next_news}></div>
            </div>

        <{/if}>
    <{/foreach}>
<{else}>
    <div class="alert alert-danger">
        <p><{$smarty.const._MD_TADNEWS_HIDDEN}></p>
    </div>
<{/if}>