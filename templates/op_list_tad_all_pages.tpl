<{if $cate_set_tool|default:false}>
    <{$toolbar|default:''}>
<{/if}>

<{$breadcrumb|default:''}>

<!--TadNews Start-->
<{foreach from=$all_news item=cate}>
    <{if $tadnews_adm or (isset($ok_cat) && $cate.ncsn|in_array:$ok_cat)}>
        <script language="JavaScript">
        $().ready(function(){
            $('#sort_<{$cate.ncsn}>').sortable({ opacity: 0.6, cursor: 'move', update: function() {
                var order = $(this).sortable('serialize');
                $.post('save_sort.php', order + '&XOOPS_TOKEN_REQUEST=<{$tadnews_token|default:''}>', function(theResponse){
                    $('#save_msg_<{$cate.ncsn}>').html(theResponse);
                });
            }
            });
        });
        </script>
    <{/if}>

    <h3>
        <{if $tadnews_adm or (isset($ok_cat) && $cate.ncsn|in_array:$ok_cat)}>
            <div class="pull-right float-right float-end">
                <{if !$cate.ncsn|in_array:$link_cate_sn_arr}>
                    <form action="page.php" method="post" class="d-inline">
                        <input type="hidden" name="op" value="add_to_menu">
                        <input type="hidden" name="ncsn" value="<{$cate.ncsn}>">
                        <input type="hidden" name="XOOPS_TOKEN_REQUEST" value="<{$tadnews_token|default:''}>">
                        <button type="submit" class="btn btn-success"><{$smarty.const._MD_TADNEWS_ADD_TO_MENU}></button>
                    </form>
                <{/if}>
                <a href="page.php?op=modify_page_cate&ncsn=<{$cate.ncsn}>" class="btn btn-warning"><{$smarty.const._MD_TADNEWS_EDIT_CATE}></a>
                <a href="post.php?ncsn=<{$cate.ncsn}>" class="btn btn-primary"><{$smarty.const._MD_TADNEWS_POST}></a>
            </div>
        <{/if}>
        <a href="page.php?ncsn=<{$cate.ncsn}>" style="text-shadow:1px 1px 1px #aaaaaa;"><{$cate.nc_title}></a>
    </h3>
    <div id="save_msg_<{$cate.ncsn}>"></div>

    <ul class="list-group" id="sort_<{$cate.ncsn}>" style="margin: 4px auto 30px;">
        <{foreach from=$cate.news item=news}>
            <li class="list-group-item d-flex justify-content-between align-items-center" <{if $tadnews_adm or (isset($ok_cat) && $cate.ncsn|in_array:$ok_cat)}>id="tr_<{$news.nsn}>"<{/if}>>

                <span class="badge badge-secondary bg-secondary rounded-pill"><{$news.page_sort}></span>
                <a href="<{$xoops_url}>/modules/tadnews/page.php?ncsn=<{$cate.ncsn}>&nsn=<{$news.nsn}>">
                    <{$news.news_title}>
                </a>
                <span class="badge  badge-pill badge-secondary"><{$news.counter}></span>
            </li>
        <{/foreach}>
    </ul>


    <{if $cate.ncsn|in_array:$link_cate_sn_arr and ($tadnews_adm or (isset($ok_cat) && $cate.ncsn|in_array:$ok_cat))}>
        <div class="alert alert-warning"><{$cate.nc_title|string_format:$smarty.const._MD_TADNEWS_ADD_TO_MENU_ALERT}></div>
    <{/if}>
<{foreachelse}>
    <h3 class="sr-only visually-hidden"><{$smarty.const._MD_TADNEWS_KIND_PAGE}></h3>
<{/foreach}>
