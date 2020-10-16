<form action="page.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">

    <div class="well" style="background-color:#EEFFCC;">
        <div class="form-group">
            <label class="col-sm-2 control-label">
            <{$smarty.const._MD_TADNEWS_CATE_TITLE}>
            </label>
            <div class="col-sm-10">
            <input type="text" name="nc_title" id="nc_title" class="form-control" value="<{$nc_title}>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">
            <{$smarty.const._MD_TADNEWS_PARENT_CATE}>
            </label>
            <div class="col-sm-4">
            <select name="of_ncsn" size=1 id="of_ncsn" class="form-control">
                <{$cate_select}>
            </select>
            </div>
            <label class="col-sm-2 control-label">
            <{$smarty.const._MD_TADNEWS_CATE_PIC}>
            </label>
            <div class="col-sm-4">
            <input type="file" id="cate_pic" name="cate_pic">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">
            <{$smarty.const._MD_TADNEWS_CAN_READ_CATE_GROUP}><br>
            <{$smarty.const._MD_TADNEWS_CAN_POST_CATE_GROUP_TXT}>
            </label>
            <div class="col-sm-4">
            <{$enable_group}>
            </div>

            <label class="col-sm-2 control-label">
            <{$smarty.const._MD_TADNEWS_CAN_POST_CATE_GROUP}><br>
            <{$smarty.const._MD_TADNEWS_CAN_POST_CATE_GROUP_TXT}>
            </label>
            <div class="col-sm-4">
            <{$enable_post_group}>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">
            <{$smarty.const._MD_TADNEWS_CATE_SHOW_TITLE}><{$smarty.const._TAD_FOR}>
            </label>
            <div class="col-sm-4">
            <label class="radio-inline">
                <input type="radio" name="setup[title]" id="setup_title_1" value="1" <{if $title!='0'}>checked<{/if}>>
                <{$smarty.const._YES}>
            </label>

            <label class="radio-inline">
                <input type="radio" name="setup[title]" id="setup_title_0" value="0" <{if $title=='0'}>checked<{/if}>>
                <{$smarty.const._NO}>
            </label>
            </div>

            <label class="col-sm-2 control-label">
            <{$smarty.const._MD_TADNEWS_CATE_SHOW_TOOL}><{$smarty.const._TAD_FOR}>
            </label>
            <div class="col-sm-4">
            <label class="radio-inline">
                <input type="radio" name="setup[tool]" id="setup_tool_1" value="1" <{if $tool!='0'}>checked<{/if}>>
                <{$smarty.const._YES}>
            </label>

            <label class="radio-inline">
                <input type="radio" name="setup[tool]" id="setup_tool_0" value="0" <{if $tool=='0'}>checked<{/if}>>
                <{$smarty.const._NO}>
            </label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">
            <{$smarty.const._MD_TADNEWS_CATE_SHOW_COMM}><{$smarty.const._TAD_FOR}>
            </label>
            <div class="col-sm-4">
            <label class="radio-inline">
                <input type="radio" name="setup[comm]" id="setup_comm_1" value="1" <{if $comm=='1'}>checked<{/if}>>
                <{$smarty.const._YES}>
            </label>

            <label class="radio-inline">
                <input type="radio" name="setup[comm]" id="setup_comm_0" value="0" <{if $comm!='1'}>checked<{/if}>>
                <{$smarty.const._NO}>
            </label>
            </div>

            <label class="col-sm-2 control-label">
            <{$smarty.const._MD_TADNEWS_CATE_SHOW_NAV}><{$smarty.const._TAD_FOR}>
            </label>
            <div class="col-sm-4">
            <label class="radio-inline">
                <input type="radio" name="setup[nav]" id="setup_nav_1" value="1" <{if $nav!='0'}>checked<{/if}>>
                <{$smarty.const._YES}>
            </label>

            <label class="radio-inline">
                <input type="radio" name="setup[nav]" id="setup_nav_0" value="0" <{if $nav=='0'}>checked<{/if}>>
                <{$smarty.const._NO}>
            </label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">
                <{$smarty.const._MD_TADNEWS_CATE_SHOW_PATH}><{$smarty.const._TAD_FOR}>
            </label>
            <div class="col-sm-4">
                <label class="radio-inline">
                    <input type="radio" name="setup[breadcrumbs]" id="setup_breadcrumbs_1" value="1" <{if $breadcrumbs=='1'}>checked<{/if}>>
                    <{$smarty.const._YES}>
                </label>

                <label class="radio-inline">
                    <input type="radio" name="setup[breadcrumbs]" id="setup_breadcrumbs_0" value="0" <{if $breadcrumbs!='1'}>checked<{/if}>>
                    <{$smarty.const._NO}>
                </label>
            </div>
        </div>
    </div>

    <div class="form-group text-center">
        <input type="hidden" name="not_news" value="1">
        <input type="hidden" name="ncsn" value="<{$ncsn}>">
        <input type="hidden" name="op" value="<{$cate_op}>">
        <{$XOOPS_TOKEN}>
        <button type="submit" class="btn btn-info"><{if $ncsn==""}><{$smarty.const._MD_TADNEWS_ADD_CATE}><{else}><{$smarty.const._TAD_SAVE}><{/if}></button>
    </div>
</form>