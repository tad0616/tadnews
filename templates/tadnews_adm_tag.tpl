<div class="container-fluid">
    <form action="tag.php" method="post" id="myForm" class="form-horizontal" role="form">
        <table class="table table-striped table-hover table-shadow">
            <tr>
                <th><{$smarty.const._MA_TADNEWS_TAG_DEMO}></th>
                <th><{$smarty.const._MA_TADNEWS_TAG_TITLE}></th>
                <th><{$smarty.const._MA_TADNEWS_TAG_FONTCOLOR}></th>
                <th><{$smarty.const._MA_TADNEWS_TAG_COLOR}></th>
                <th><{$smarty.const._MA_TADNEWS_TAG_ENABLE}></th>
                <th><{$smarty.const._MA_TADNEWS_TAG_FUNC}></th>
            </tr>

            <{if $tag_sn==""}>
                <tr style="background-color: antiquewhite;">
                    <td><{$smarty.const._MA_TADNEWS_TAG_NEW}></td>
                    <td><input type="text" name="tag" value="<{$tag|default:''}>" class="form-control"></td>
                    <td>
                        <div class="input-group">
                            <input type="text" name="font_color" class="form-control color-picker" value="<{$font_color|default:''}>" id="font_color"  data-hex="true">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" name="color" class="form-control color-picker" value="<{$color|default:''}>" id="color"  data-hex="true">
                        </div>
                    </td>
                    <td>
                        <div class="form-check-inline radio-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="enable" value="1" <{if $enable!='0'}>checked<{/if}>>
                                <{$smarty.const._YES}>
                            </label>
                        </div>
                        <div class="form-check-inline radio-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="enable" value="0" <{if $enable=='0'}>checked<{/if}>>
                                <{$smarty.const._NO}>
                            </label>
                        </div>
                    </td>
                    <td>
                        <input type="hidden" name="op" value="insert_tad_news_tags">
                        <{$XOOPS_TOKEN|default:''}>
                        <button class="btn btn-sm btn-xs btn-primary" type="submit"><{$smarty.const._MA_TADNEWS_SAVE_CATE}></button>
                    </td>
                </tr>
            <{/if}>

            <{foreach from=$tagarr item=tag}>
                <{if $tag.mode=="edit"}>
                    <tr style="background-color: antiquewhite;">
                        <td><{$tag.prefix_tag}></td>
                        <td><input type="text" name="tag" value="<{$tag.tag}>" class="form-control"></td>

                        <td>
                            <div class="input-group">
                                <input type="text" name="font_color" class="form-control color-picker" value="<{$tag.font_color}>" id="font_color" data-hex="true">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="text" name="color" class="form-control color-picker" value="<{$tag.color}>" id="color" data-hex="true">
                            </div>
                        </td>
                        <td>
                            <div class="form-check-inline radio-inline">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="enable" value="1" <{if $tag.checked=='1' or $tag.checked==''}>checked<{/if}>>
                                    <{$smarty.const._YES}>
                                </label>
                            </div>
                            <div class="form-check-inline radio-inline">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="enable" value="0" <{if $tag.checked=='0'}>checked<{/if}>>
                                    <{$smarty.const._NO}>
                                </label>
                            </div>
                        </td>
                        <td>
                        <input type="hidden" name="tag_sn" value="<{$tag_sn|default:''}>">
                        <input type="hidden" name="op" value="update_tad_news_tags">
                        <{$XOOPS_TOKEN|default:''}>
                        <button class="btn btn-sm btn-xs btn-primary" type="submit"><{$smarty.const._MA_TADNEWS_SAVE_CATE}></button>
                        </td>
                    </tr>
                <{else}>
                    <tr>
                        <td><{$tag.prefix_tag}></td>
                        <td><{$tag.tag}></td>
                        <td><{$tag.font_color}></td>
                        <td><{$tag.color}></td>
                        <td><{$tag.enable_txt}><{$tag.amount}></td>
                        <td>
                            <a href='tag.php?tag_sn=<{$tag.tag_sn}>' class='btn btn-sm btn-xs btn-info'><i class="fa fa-pencil" aria-hidden="true"></i>  <{$smarty.const._TAD_EDIT}></a>
                            <{if $tag.enable|default:false}>
                                <a href='tag.php?op=stat&enable=0&tag_sn=<{$tag.tag_sn}>' class='btn btn-sm btn-xs btn-warning'><{$smarty.const._MA_TADNEWS_TAG_UNABLE}></a>
                            <{else}>
                                <a href='tag.php?op=stat&enable=1&tag_sn=<{$tag.tag_sn}>' class='btn btn-sm btn-xs btn-success'><{$smarty.const._MA_TADNEWS_TAG_ABLE}></a>
                            <{/if}>
                            <{if !$tag.enable and $tag.tag_amount == 0}>
                                <a href='javascript:delete_tag(<{$tag.tag_sn}>);' class='btn btn-sm btn-xs btn-danger'><i class="fa fa-times"></i> <{$smarty.const._TAD_DEL}></a>
                            <{/if}>
                        </td>
                    </tr>
                <{/if}>
            <{/foreach}>

        </table>
    </form>
</div>