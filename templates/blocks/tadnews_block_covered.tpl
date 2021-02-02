<{if $block.page}>
    <div class="row">
        <{foreach from=$block.page item=news  name=nn}>
            <{assign var="i" value=$smarty.foreach.nn.iteration|substr:-1}>
            <div class="col-sm-<{$block.num}>" style="margin-bottom:16px;">
                <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$news.nsn}>">
                    <div style="height:150px;overflow:hidden;border:1px solid gray;background-image: url('<{if $news.image_big}><{$news.image_big}><{else}><{$xoops_url}>/modules/tadnews/images/demo<{$i}>.jpg<{/if}>'); background-size: cover;background-position: center center;">
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