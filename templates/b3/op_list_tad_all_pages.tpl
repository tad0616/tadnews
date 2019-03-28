<!--TadNews Start-->
<{if $ncsn}>
    <link rel="alternate" type="application/rss+xml" title="tadnews rss" href="<{$xoops_url}>/modules/tadnews/rss.php?ncsn=<{$ncsn}>" />
<{/if}>
<link rel="stylesheet" type="text/css" media="screen" href="<{$xoops_url}>/modules/tadtools/css/iconize.css" />

<{$toolbar}>

<{if $isAdmin or $isOwner}>
    <script language="JavaScript">
    $().ready(function(){
        $('#sort').sortable({ opacity: 0.6, cursor: 'move', update: function() {
            var order = $(this).sortable('serialize');
            $.post('save_sort.php', order, function(theResponse){
                $('#save_msg').html(theResponse);
            });
        }
        });
    });
    </script>
<{/if}>

<!--TadNews Start-->
<{foreach item=all_news from=$all_news}>

    <h3>
    <{if $isAdmin or $isOwner}>
        <div class="pull-right">
            <{if $show_add_to_menu=='1'}>
                <a href="page.php?op=add_to_menu&ncsn=<{$all_news.ncsn}>" class="btn btn-success"><{$smarty.const._MD_TADNEWS_ADD_TO_MENU}></a>
            <{/if}>
            <a href="page.php?op=modify_page_cate&ncsn=<{$all_news.ncsn}>" class="btn btn-warning"><{$smarty.const._MD_TADNEWS_EDIT_CATE}></a>
            <a href="post.php?ncsn=<{$all_news.ncsn}>" class="btn btn-primary"><{$smarty.const._MD_TADNEWS_POST}></a>
        </div>
    <{/if}>
    <a href="page.php?ncsn=<{$all_news.ncsn}>" style="text-shadow:1px 1px 1px #aaaaaa;"><{$all_news.nc_title}></a>
    </h3>
    <div id="save_msg"></div>

    <ul  class="list-group" id="sort">
        <{foreach from=$all_news.news item=news}>
            <li class="list-group-item d-flex justify-content-between align-items-center" <{if $isAdmin or $isOwner}>id="tr_<{$news.nsn}>"<{/if}>>
                <a href="<{$xoops_url}>/modules/tadnews/page.php?nsn=<{$news.nsn}>">
                    <{$news.news_title}>
                </a>
                <span class="badge  badge-pill badge-secondary"><{$news.counter}></span>
            </li>
        <{/foreach}>
    </ul>



    <{if $show_add_to_menu!='1' and ($isAdmin or $isOwner)}>
        <div class="alert alert-warning"><{$smarty.const._MD_TADNEWS_ADD_TO_MENU_ALERT}></div>
    <{/if}>

<{/foreach}>
