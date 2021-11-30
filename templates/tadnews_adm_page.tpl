<script language="JavaScript">
    $().ready(function(){
        $("#clickAll").click(function() {
            var x = document.getElementById("clickAll").checked;
            if(x){
                $(".news").each(function() {
                    $(this).prop("checked", true);
                });
            }else{
                $(".news").each(function() {
                    $(this).prop("checked", false);
                });
            }
            coint_checked();
        });

        $(".news").change(function(event) {
            coint_checked();
        });

        $('#sort').sortable({ opacity: 0.6, cursor: 'move', update: function() {
            var order = $(this).sortable('serialize');
            $.post('../save_sort.php', order, function(theResponse){
                $('#save_msg').html(theResponse);
            });
            }
        });

    });

    function coint_checked(){
        var $b = $('.news');
        if($b.filter(':checked').length > 0 ){
            $('#batch_tool').show();
        }else{
            $('#batch_tool').hide();
        }
    }

    function check_one(id_name,change){
        if(document.getElementById(id_name).checked && change){
            document.getElementById(id_name).checked = false;
        }else{
            document.getElementById(id_name).checked = true;
        }
    }
</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div id="save_msg"></div>
            <div style="max-height: 300px; overflow: auto;">
                <{$ztree_code}>
            </div>

            <{if $ncsn}>
                <div class="alert alert-info">
                    <div class="pull-right float-right">
                        <{if $now_op!="tad_news_cate_form" and $ncsn}>
                            <{if !$cate.count}>
                                <a href="javascript:delete_tad_news_cate_func(<{$cate.ncsn}>);" class="btn btn-sm btn-xs btn-danger <{if $cate.count > 0}>disabled<{/if}>"><i class="fa fa-times"></i> <{$smarty.const._TAD_DEL}></a>
                            <{/if}>
                            <a href="page.php?op=modify_page_cate&ncsn=<{$ncsn}>" class="btn btn-sm btn-xs btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}></a>
                        <{/if}>
                    </div>
                    <h3 class="my">
                        <{includeq file="$xoops_rootpath/modules/tadnews/templates/sub_cate_pic.tpl"}>
                        <a href="../page.php?ncsn=<{$cate.ncsn}>"><{$cate.nc_title}></a>
                    </h3>
                    <ul>
                        <li style="line-height:2;"><{$smarty.const._MA_TADNEWS_CATE_COUNTER}><{$smarty.const._TAD_FOR}><{$cate.count}></li>
                        <li style="line-height:2;"><{$smarty.const._MA_TADNEWS_CAN_READ_CATE_GROUP_S}><{$smarty.const._TAD_FOR}><{$cate.g_txt}></li>
                        <li style="line-height:2;"><{$smarty.const._MA_TADNEWS_CAN_POST_CATE_GROUP_S}><{$smarty.const._TAD_FOR}><{$cate.gp_txt}></li>
                    </ul>
                </div>
            <{/if}>

            <div class="d-grid gap-2">
                <{if $now_op!="tad_news_cate_form" or $ncsn!=""}>
                    <a href="page.php?op=modify_page_cate" class="btn btn-primary btn-block">
                        <i class="fa fa-plus"></i> <{$smarty.const._MA_TADNEWS_ADD_CATE}>
                    </a>
                <{/if}>
            </div>
        </div>
        <div class="col-md-9">
            <{if $now_op=="modify_page_cate"}>
                <{includeq file="$xoops_rootpath/modules/tadnews/templates/op_`$now_op`.tpl"}>
            <{elseif $page}>
                <{if $ncsn!=""}>
                    <div class="row">
                        <div class="col-md-6">
                            <h1 class="my">
                                <{includeq file="$xoops_rootpath/modules/tadnews/templates/sub_cate_pic.tpl"}>
                                <a href="../page.php?ncsn=<{$cate.ncsn}>"><{$cate.nc_title}></a>
                            </h1>
                        </div>
                        <div class="col-md-6 text-right text-end">
                            <div style="margin: 10px;">
                                <{if $now_op!="tad_news_cate_form" and $ncsn}>
                                    <a href="javascript:delete_tad_news_cate_func(<{$cate.ncsn}>);" class="btn btn-danger <{if $cate.count > 0}>disabled<{/if}>"><i class="fa fa-times"></i> <{$smarty.const._TAD_DEL}></a>
                                    <a href="<{$php_self}>?not_news=0&op=change_kind&ncsn=<{$ncsn}>" class="btn btn-success"><i class="fa fa-retweet"></i> <{$smarty.const._MA_TADNEWS_CHANGE_TO_NEWS}></a>
                                    <a href="page.php?op=modify_page_cate&ncsn=<{$ncsn}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}></a>
                                <{/if}>
                            </div>
                        </div>
                    </div>
                <{else}>
                    <h1 class="my"><{$smarty.const._MA_TADNEWS_ALL_NEWS}></h1>
                <{/if}>
                <form action="page.php" method="post" class="form-horizontal" role="form">
                    <table class="table table-striped table-hover table-shadow">
                        <tr>
                            <th nowrap>
                                <div class="form-check-inline checkbox-inline">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" id="clickAll">
                                        <{$smarty.const._MA_TADNEWS_NEWS_CATE}>
                                    </label>
                                </div>
                            </th>
                            <th nowrap>
                                <{$smarty.const._MA_TADNEWS_NEWS_TITLE}>
                            </th>
                            <{if $show_admin_tool_title}>
                                <th nowrap><{$smarty.const._MA_TADNEWS_CAN_READ_NEWS_GROUP}></th>
                                <th nowrap><{$smarty.const._MA_TADNEWS_FUNCTION}></th>
                            <{/if}>
                        </tr>
                        <tbody id="sort">
                            <{foreach item=page from=$page}>
                                <tr id="tr_<{$page.nsn}>">
                                    <td>
                                        <div class="form-check-inline checkbox-inline">
                                            <label class="form-check-label">
                                                <input name="nsn_arr[]" value="<{$page.nsn}>" type="checkbox" class="news form-check-input">
                                                <a href="page.php?ncsn=<{$page.ncsn}>" title="<{$page.cate_name}>"><{$page.cate_name}></a>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <{$page.prefix_tag}>
                                        <{if $page.need_sign}>
                                            <img src="<{$page.need_sign}>" alt="<{$page.news_title}>" style="margin:3px;">
                                        <{/if}>
                                        <{$page.enable_txt}>
                                        <{$page.today_pic}>
                                        <{$page.post_date}>
                                        <a href="<{$xoops_url}>/modules/tadnews/<{$page.link_page}>?nsn=<{$page.nsn}>"><{$page.news_title}></a>
                                        <span style="color:gray;font-size: 0.8rem;"> (<a href="page.php?show_uid=<{$page.uid}>"><{$page.uid_name}></a> / <{$page.counter}>)</span> <{$page.passwd}>
                                    </td>
                                    <{if $page.show_admin_tool}>
                                        <td><{$page.g_txt}></td>
                                        <td nowrap>
                                            <a href="javascript:delete_tad_news_func(<{$page.nsn}>);" class="btn btn-sm btn-xs btn-danger" id="del<{$page.nsn}>"><i class="fa fa-times"></i> <{$smarty.const._TAD_DEL}></a>
                                            <a href="<{$xoops_url}>/modules/tadnews/post.php?op=tad_news_form&nsn=<{$page.nsn}>" class="btn btn-sm btn-xs btn-warning" id="update<{$page.nsn}>"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}></a>
                                        </td>
                                    <{/if}>
                                </tr>
                            <{/foreach}>
                        </tbody>
                    </table>
                    <p class="my-5">
                        <{$bar}>
                    </p>

                    <div class="alert alert-info" id="batch_tool" style="display: none;">
                        <div class="form-group row mb-3">
                            <label class="col-md-2 control-label col-form-label text-md-right">
                                <input type='radio' name='act' value='del_news'>
                                <{$smarty.const._TAD_DEL}>
                            </label>
                            <label class="col-md-2 control-label col-form-label text-md-right">
                                <input type='radio' name='act' value='move_news' id="move">
                                <{$smarty.const._TADNEWS_MOVE_TO}>
                            </label>
                            <div class="col-md-4">
                                <select name='ncsn' class="form-control" onChange="check_one('move',false)"><{$options}></select>
                            </div>
                            <div class="col-md-3">
                                <input type='hidden' name='kind' value='news'>
                                <input type='hidden' name='op' value='batch'>
                                <{$XOOPS_TOKEN}>
                                <button type='submit' class='btn btn-primary'><{$smarty.const._TAD_SUBMIT}></button>
                            </div>
                        </div>
                    </div>

                </form>
            <{else}>
                <div class="alert alert-danger text-center">
                    <h3 class="my"><{$smarty.const._MA_TADNEWS_NO_NEWS}></h3>
                </div>
            <{/if}>
        </div>
    </div>
</div>
