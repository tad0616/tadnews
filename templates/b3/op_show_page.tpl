<{if $cate_set_tool}>
  <p><{$toolbar}></p>
<{/if}>

<{if $cate_set_breadcrumbs=='1'}>
    <{$breadcrumb}>
<{/if}>

<{$rating_js}>
<{$del_js}>
<{$jquery}>

<{if $page}>

    <div class="row">
        <div class="col-md-12">
        <{foreach item=page from=$page}>
            <{if $cate_set_title}>
            <h1 style="text-shadow:1px 1px 1px #aaaaaa;">
                <{$page.news_title}>
            </h1>
            <div class="pull-right" style="border-left: 1px solid #cfcfcf; padding-left:8px;">
                <a href="page.php?ncsn=<{$page.ncsn}>"><{$page.cate_name}></a> /
                <{$page.post_date}> /
                <{$smarty.const._TADNEWS_HOT}>
                <{$page.counter}>
            </div>
            <hr>
            <{/if}>


            <div style="margin:10px auto; line-height: 2em; font-weight:normal;"><{$page.pic}><{$page.content}></div>

            <{$page.files}>

            <div class="text-right">
            <{$page.fun}>
            </div>

            <{if $cate_set_nav}>
            <div class="row" style="margin:10px;">
                <div class="col-md-6">
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
            <{/if}>

            <{if $cate_set_comm}>
            <div class="row">
                <p style="clear: both">
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
                </p>
            </div>
            <{/if}>

        <{/foreach}>
        </div>
    </div>
<{else}>
    <div class="alert alert-danger">
        <p><{$smarty.const._MD_TADNEWS_HIDDEN}></p>
    </div>
<{/if}>
