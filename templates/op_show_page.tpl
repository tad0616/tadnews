<{if $cate_set_tool|default:false}>
    <p><{$toolbar}></p>
<{/if}>

<{if $cate_set_breadcrumbs=='1'}>
    <{$breadcrumb}>
<{/if}>

<{$rating_js}>
<{if $page|default:false}>

    <div class="row">
        <div class="col-md-12">
        <{foreach from=$page item=news}>
            <{if $cate_set_title|default:false}>
                <h1 style="text-shadow:1px 1px 1px #aaaaaa;">
                    <{$news.news_title}>
                </h1>
                <div class="pull-right float-right float-end" style="border-left: 1px solid #cfcfcf; padding-left:8px;">
                    <a href="page.php?ncsn=<{$news.ncsn}>"><{$news.cate_name}></a> /
                    <{$news.post_date}> /
                    <{$smarty.const._TADNEWS_HOT}>
                    <{$news.counter}>
                </div>
                <hr>
            <{else}>
                <h2 class="sr-only visually-hidden"><{$news.news_title}></h2>
            <{/if}>

            <div style="margin:10px auto; line-height: 2rem; font-weight:normal;"><{$news.pic}><{$news.content}></div>

            <{$news.files}>

            <div class="text-right text-end">
            <{$news.fun}>
            </div>

            <{if $cate_set_nav|default:false}>
                <div class="row" style="margin:10px;">
                    <div class="col-md-6 d-grid gap-2">
                    <{if $news.back_news_link|default:false}>
                        <a href="<{$news.back_news_link}>" class="btn btn-default btn-outline-info btn-block"><img src="images/left.png" hspace=2 alt="Previous"><{$news.back_news_title}></a>
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
        </div>
    </div>
<{else}>
    <h2 class="sr-only visually-hidden"><{$smarty.const._MD_TADNEWS_HIDDEN}></h2>
    <div class="alert alert-danger">
        <p><{$smarty.const._MD_TADNEWS_HIDDEN}></p>
    </div>
<{/if}>
