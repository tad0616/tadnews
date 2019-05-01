<div class="container-fluid">
    <form action="tag.php" method="post" id="myForm" role="form">
        <table class="table table-striped table-bordered">
        <tr>
            <th><{$smarty.const._MA_TADNEWS_TAG_DEMO}></th>
            <th><{$smarty.const._MA_TADNEWS_TAG_TITLE}></th>
            <th><{$smarty.const._MA_TADNEWS_TAG_FONTCOLOR}></th>
            <th><{$smarty.const._MA_TADNEWS_TAG_COLOR}></th>
            <th><{$smarty.const._MA_TADNEWS_TAG_ENABLE}></th>
            <th><{$smarty.const._MA_TADNEWS_TAG_FUNC}></th>
        </tr>

        <{if $tag_sn==""}>
            <tr class="info">
            <td><{$smarty.const._MA_TADNEWS_TAG_NEW}></td>
            <td><input type="text" name="tag" value="<{$tag}>" class="form-control"></td>
            <td>
                <input type="text" name="font_color" class="form-control color" value="<{$font_color}>" id="font_color" data-text="hidden" data-hex="true">
            </td>
            <td>
                <input type="text" name="color" class="form-control color" value="<{$color}>" id="color" data-text="hidden" data-hex="true">
            </td>
            <td>
                <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="enable" id="enable_1" value="1" <{if $enable!='0'}>checked<{/if}>>
                <label class="form-check-label" for="enable_1"><{$smarty.const._YES}></label>
                </div>
                <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="enable" id="enable_0" value="0" <{if $enable=='0'}>checked<{/if}>>
                <label class="form-check-label" for="enable_0"><{$smarty.const._NO}></label>
                </div>
            </td>
            <td>
                <input type="hidden" name="op" value="insert_tad_news_tags">
                <{$XOOPS_TOKEN}>
                <button class="btn btn-sm btn-primary" type="submit"><{$smarty.const._MA_TADNEWS_SAVE_CATE}></button>
            </td>
            </tr>
        <{/if}>

        <{foreach item=tag from=$tagarr}>
            <{if $tag.mode=="edit"}>
            <tr class="warning">
                <td><{$tag.prefix_tag}></td>
                <td><input type="text" name="tag" value="<{$tag.tag}>" class="form-control"></td>

                <td>
                <input type="text" name="font_color" class="form-control color" value="<{$tag.font_color}>" id="font_color" data-text="hidden" data-hex="true">
                </td>
                <td>
                <input type="text" name="color" class="form-control color" value="<{$tag.color}>" id="color" data-text="hidden" data-hex="true">
                </td>

                <td>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="enable" id="enable_1" value="1" <{if $tag.checked=='1' or $tag.checked==''}>checked<{/if}>>
                    <label class="form-check-label" for="enable_1"><{$smarty.const._YES}></label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="enable" id="enable_0" value="0" <{if $tag.checked=='0'}>checked<{/if}>>
                    <label class="form-check-label" for="enable_0"><{$smarty.const._NO}></label>
                </div>
                </td>
                <td>
                <input type="hidden" name="tag_sn" value="<{$tag_sn}>">
                <input type="hidden" name="op" value="update_tad_news_tags">
                <{$XOOPS_TOKEN}>
                <button class="btn btn-sm btn-primary" type="submit"><{$smarty.const._MA_TADNEWS_SAVE_CATE}></button>
                </td></tr>
            <{else}>
                <tr>
                    <td><{$tag.prefix_tag}></td>
                    <td><{$tag.tag}></td>
                    <td><{$tag.font_color}></td>
                    <td><{$tag.color}></td>
                    <td><{$tag.enable_txt}><{$tag.amount}></td>
                    <td>
                        <a href='tag.php?tag_sn=<{$tag.tag_sn}>' class='btn btn-sm btn-info'><{$smarty.const._TADNEWS_EDIT}></a>
                        <{if $tag.enable}>
                            <a href='tag.php?op=stat&enable=0&tag_sn=<{$tag.tag_sn}>' class='btn btn-sm btn-warning'><{$smarty.const._MA_TADNEWS_TAG_UNABLE}></a>
                        <{else}>
                            <a href='tag.php?op=stat&enable=1&tag_sn=<{$tag.tag_sn}>' class='btn btn-sm btn-success'><{$smarty.const._MA_TADNEWS_TAG_ABLE}></a>
                        <{/if}>
                        <{if $tag.enable!= '1' and $tag.tag_amount == 0}>
                            <a href='javascript:delete_tag(<{$tag.tag_sn}>);' class='btn btn-sm btn-danger'><{$smarty.const._TADNEWS_DEL}></a>
                        <{/if}>
                    </td>
                </tr>
            <{/if}>
        <{/foreach}>

        </table>
    </form>
</div>