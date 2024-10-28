<h1 class="my"><{$smarty.const._MD_TADNEWS_EDIT_CATE}></h1>
<form action="main.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
    <div class="alert alert-success">
        <div class="form-group row mb-3">
            <label class="col-md-2 control-label col-form-label text-md-right text-md-end">
                <{$smarty.const._MA_TADNEWS_CATE_TITLE}>
            </label>
            <div class="col-md-10">
                <input type="text" name="nc_title" id="nc_title" class="form-control" value="<{$nc_title|default:''}>">
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-2 control-label col-form-label text-md-right text-md-end">
                <{$smarty.const._MA_TADNEWS_PARENT_CATE}>
            </label>
            <div class="col-md-4">
                <select name="of_ncsn" size=1 id="of_ncsn" class="form-select">
                <{$cate_select|default:''}>
                </select>
            </div>
            <label class="col-md-2 control-label col-form-label text-md-right text-md-end">
                <{$smarty.const._MA_TADNEWS_CATE_PIC}>
            </label>
            <div class="col-md-4">
                <input type="file" id="cate_pic" name="cate_pic" class="form-control">
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-2 control-label col-form-label text-md-right text-md-end">
                <{$smarty.const._MA_TADNEWS_CAN_READ_CATE_GROUP}><br>
                <{$smarty.const._MA_TADNEWS_CAN_POST_CATE_GROUP_TXT}>
            </label>
            <div class="col-md-4">
                <{$enable_group|default:''}>
            </div>

            <label class="col-md-2 control-label col-form-label text-md-right text-md-end">
                <{$smarty.const._MA_TADNEWS_CAN_POST_CATE_GROUP}><br>
                <{$smarty.const._MA_TADNEWS_CAN_POST_CATE_GROUP_TXT}>
            </label>
            <div class="col-md-4">
                <{$enable_post_group|default:''}>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-2 control-label col-form-label text-md-right text-md-end">
                <{$smarty.const._MA_TADNEWS_NO_PERMISSION}><{$smarty.const._TAD_FOR}>
            </label>
            <div class="col-md-4">
                <div class="form-check-inline radio-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="setup[only_title]" value="1" <{if $only_title!='0'}>checked<{/if}>>
                        <{$smarty.const._MA_TADNEWS_DISPLAY_TITLE}>
                    </label>
                </div>
                <div class="form-check-inline radio-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="setup[only_title]" value="0" <{if $only_title=='1'}>checked<{/if}>>
                        <{$smarty.const._MA_TADNEWS_HIDE_ARTICLE}>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <input type="hidden" name="not_news" value="0">
        <input type="hidden" name="ncsn" value="<{$ncsn|default:''}>">
        <input type="hidden" name="op" value="<{$cate_op|default:''}>">
        <{$XOOPS_TOKEN|default:''}>
        <button type="submit" class="btn btn-info">
        <{if $ncsn==""}>
            <{$smarty.const._MA_TADNEWS_ADD_CATE}>
        <{else}>
            <{$smarty.const._TAD_SAVE}>
        <{/if}>
        </button>
    </div>
</form>