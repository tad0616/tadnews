<{if $block.page}>
    <{if $block.display_mode==2}>
        <{foreach from=$block.page item=news  name=nn}>
            <{assign var="i" value=$smarty.foreach.nn.iteration|substr:-1}>
            <div class="row" style="margin-bottom: 0.6rem;">
                <div class="col-lg-<{$block.width_left}>">
                    <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$news.nsn}>">
                        <div style="height:<{$block.height}>;overflow:hidden;border:1px solid gray;background-image: url('<{if $news.image_big}><{$news.image_big}><{else}><{$block.demo_path}>/demo<{$i}>.jpg<{/if}>'); background-size: cover;background-position: center center; color: transparent;"><{$news.news_title}>
                        </div>
                    </a>
                </div>
                <div class="col-lg-<{$block.width_right}>">
                    <h4 class="my">
                        <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$news.nsn}>"><{$news.news_title}></a>
                    </h4>
                    <div style="<{$block.summary_css}>">
                        <{$news.content}>
                    </div>
                </div>
            </div>
        <{/foreach}>
    <{else}>
        <div class="row">
            <{foreach from=$block.page item=news  name=nn}>
                <{assign var="i" value=$smarty.foreach.nn.iteration|substr:-1}>
                <div class="col-sm-<{$block.num}>" style="margin-bottom:16px;">
                    <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$news.nsn}>">
                        <div style="height:150px;overflow:hidden;border:1px solid gray;background-image: url('<{if $news.image_big}><{$news.image_big}><{else}><{$block.demo_path}>/demo<{$i}>.jpg<{/if}>'); background-size: cover;background-position: center center; color: transparent;"><{$news.news_title}>
                        </div>
                    </a>
                    <h5 class="my">
                        <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$news.nsn}>"><{$news.news_title}></a>
                    </h5>
                    <div style="<{$block.summary_css}>">
                        <{$news.content}>
                    </div>
                </div>
            <{/foreach}>
        </div>
    <{/if}>
<{/if}>
