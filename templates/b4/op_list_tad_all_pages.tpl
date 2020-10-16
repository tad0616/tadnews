<!--TadNews Start-->
<{if $ncsn}>
    <link rel="alternate" type="application/rss+xml" title="tadnews rss" href="<{$xoops_url}>/modules/tadnews/rss.php?ncsn=<{$ncsn}>">
<{/if}>
<link rel="stylesheet" type="text/css" media="screen" href="<{$xoops_url}>/modules/tadtools/css/iconize.css">

<{if $cate_set_tool}>
  <p><{$toolbar}></p>
<{/if}>


<{$breadcrumb}>


<!--TadNews Start-->
<{foreach item=all_news from=$all_news}>
    <{if $isAdmin or $all_news.ncsn|in_array:$ok_cat}>
    <script language="JavaScript">
    $().ready(function(){
        $('#sort_<{$all_news.ncsn}>').sortable({ opacity: 0.6, cursor: 'move', update: function() {
            var order = $(this).sortable('serialize');
            $.post('save_sort.php', order, function(theResponse){
                $('#save_msg_<{$all_news.ncsn}>').html(theResponse);
            });
        }
        });
    });
    </script>
    <{/if}>

    <div class="h3">
    <{if $isAdmin or $all_news.ncsn|in_array:$ok_cat}>
        <div class="pull-right">
            <{if !$all_news.ncsn|in_array:$link_cate_sn_arr}>
                <a href="page.php?op=add_to_menu&ncsn=<{$all_news.ncsn}>" class="btn btn-success"><{$smarty.const._MD_TADNEWS_ADD_TO_MENU}></a>
            <{/if}>
            <a href="page.php?op=modify_page_cate&ncsn=<{$all_news.ncsn}>" class="btn btn-warning"><{$smarty.const._MD_TADNEWS_EDIT_CATE}></a>
            <a href="post.php?ncsn=<{$all_news.ncsn}>" class="btn btn-primary"><{$smarty.const._MD_TADNEWS_POST}></a>
        </div>
    <{/if}>
    <a href="page.php?ncsn=<{$all_news.ncsn}>" style="text-shadow:1px 1px 1px #aaaaaa;"><{$all_news.nc_title}></a>
    </div>
    <div id="save_msg_<{$all_news.ncsn}>"></div>

    <ul class="list-group" id="sort_<{$all_news.ncsn}>" style="margin: 4px auto 30px;">
        <{foreach from=$all_news.news item=news}>
            <li class="list-group-item d-flex justify-content-between align-items-center" <{if $isAdmin or $all_news.ncsn|in_array:$ok_cat}>id="tr_<{$news.nsn}>"<{/if}>>
                <a href="<{$xoops_url}>/modules/tadnews/page.php?ncsn=<{$all_news.ncsn}>&nsn=<{$news.nsn}>">
                    <{$news.news_title}>
                </a>
                <span class="badge  badge-pill badge-secondary"><{$news.counter}></span>
            </li>
        <{/foreach}>
    </ul>


    <{if !$all_news.ncsn|in_array:$link_cate_sn_arr and ($isAdmin or $all_news.ncsn|in_array:$ok_cat)}>
        <div class="alert alert-warning"><{$smarty.const._MD_TADNEWS_ADD_TO_MENU_ALERT|sprintf:$all_news.nc_title}></div>
    <{/if}>

<{/foreach}>
