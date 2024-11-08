
<{if $pic|default:false}>
    <script type="text/javascript">
        d = new Date();
        $(function() {
        $('#show_pic_css_setup').show();
        $('#upfile2').change(function() {
            $(this).upload('demo_upload.php',{op:'upload' , nsn:'<{$nsn|default:''}>'}, function(res) {
            $('#files_sn').val(res);
            $.post("demo_upload.php", { op: "get_pic", files_sn: res }, function(data) {
                $('#demo_cover_pic').css('background-image','url('+data+'?'+d.getTime()+')');
            });
            }, 'html');
        });
        });
    </script>

<{else}>

    <{assign var=pic value="<div id='demo_cover_pic' style='width:200px; height:150px; border:1px solid #909090; background-position:center center; background-repeat:no-repeat; background-size:cover; float:right; margin:4px;'></div>"}>

    <script type="text/javascript">
        d = new Date();
        $(function() {
        $('#show_pic_css_setup').hide();
        $('#upfile2').change(function() {
            $(this).upload('demo_upload.php',{op:'upload' , nsn:'<{$nsn|default:''}>'}, function(res) {
            $('#show_pic_css_setup').show();
            $('#files_sn').val(res);

            $.post("demo_upload.php", { op: "get_pic", files_sn: res }, function(data) {
                $('#demo_cover_pic').css('background-image','url('+data+'?'+d.getTime()+')');
            });
            }, 'html');
        });
        });
    </script>
<{/if}>

<input type="hidden" name="files_sn" id="files_sn" value="<{$files_sn|default:''}>">

<div class="row">
    <div class="col-md-12">
        <label><{$smarty.const._MD_TADNEWS_NEWS_PIC}></label>
        <input id="upfile2" type="file" name="upfile2" multiple>
        <div class="alert alert-success mt-2">
            <{$smarty.const._MD_TADNEWS_NEWS_PIC_NOTE}>
        </div>
    </div>
</div>

<div id="show_pic_css_setup">
    <div class="row">
        <div class="col-md-6">
            <label>
                <{$smarty.const._MD_TADNEWS_ENABLE_NEWSPIC}>
            </label>
            <select class="form-select" name="pic_css[use_pic_css]" id="pic_css">
                <option value="" <{if $use_pic_css==""}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_ENABLE_NEWSPIC_NO}></option>
                <option value="true" <{if $use_pic_css!=""}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_ENABLE_NEWSPIC_YES}></option>
            </select>
        </div>
        <div class="col-md-6">
            <{if $pic|default:false}>
                <a href="post.php?op=delete_cover&nsn=<{$nsn|default:''}>"><i class="fa fa-trash" aria-hidden="true"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TADNEWS_NEWSPIC_TAB}></a>
            <{/if}>
        </div>
    </div>

    <div id="pic_css_set">
        <div class="alert alert-success">
            <div class="row" style="margin: 20px 0px;">
                <div class="col-md-3">
                    <label>
                        <{$smarty.const._MD_TADNEWS_NEWSPIC_WIDTH}>
                    </label>
                    <div>
                        <input type="text" name="pic_css[width]" value="<{$pic_css_width|default:''}>" class="form-control" onChange="$('#demo_cover_pic').css('width',this.value+'px');" style="width: 60px; display: inline;"> x
                        <input type="text" name="pic_css[height]" value="<{$pic_css_height|default:''}>" class="form-control" onChange="$('#demo_cover_pic').css('height',this.value+'px');" style="width: 60px; display: inline;"> px
                    </div>
                </div>

                <div class="col-md-2">
                    <label>
                        <{$smarty.const._MD_TADNEWS_NEWSPIC_BORDER_STYTLE}>
                    </label>
                    <select class="form-select" name="pic_css[border_style]" onChange="$('#demo_cover_pic').css('border-style',this.value);">
                        <option value="solid" <{if $pic_css_border_style=="solid"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_SOLID}> </option>
                        <option value="dashed" <{if $pic_css_border_style=="dashed"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_DASHED}></option>
                        <option value="double" <{if $pic_css_border_style=="double"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_DOUBLE}></option>
                        <option value="dotted" <{if $pic_css_border_style=="dotted"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_DOTTED}></option>
                        <option value="groove" <{if $pic_css_border_style=="groove"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_GROOVE}></option>
                        <option value="ridge" <{if $pic_css_border_style=="ridge"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_RIDGE}></option>
                        <option value="inset" <{if $pic_css_border_style=="inset"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_INSET}></option>
                        <option value="outset" <{if $pic_css_border_style=="outset"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_OUTSET}></option>
                        <option value="none" <{if $pic_css_border_style=="none"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_NONE}></option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label>
                        <{$smarty.const._MD_TADNEWS_NEWSPIC_BORDER}><{$smarty.const._MD_TADNEWS_NEWSPIC_BORDER_WIDTH}>
                    </label>
                    <input type="text" name="pic_css[border_width]" value="<{$pic_css_border_width|default:''}>" class="form-control" onChange="$('#demo_cover_pic').css('border-width',this.value+'px');" style="width: 80%; display: inline;"> px
                </div>

                <div class="col-md-3">
                    <label>
                        <{$smarty.const._MD_TADNEWS_NEWSPIC_FLOAT}>
                    </label>
                    <select class="form-select" name="pic_css[float]" onChange="$('#demo_cover_pic').css('float',this.value);">
                        <option value="left" <{if $pic_css_float=="left"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_FLOAT_LEFT}></option>
                        <option value="right" <{if $pic_css_float=="right"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_FLOAT_RIGHT}></option>
                        <option value="none" <{if $pic_css_float=="none"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_FLOAT_NONE}></option>
                    </select>
                </div>

                <div class="col-md-1">
                    <label>
                        <{$smarty.const._MD_TADNEWS_NEWSPIC_BORDER_COLOR}>
                    </label>
                    <input type="text" name="pic_css[border_color]" id="border_color" value="<{$pic_css_border_color|default:''}>" data-text="hidden" data-hex="true" style="height: 40px; width: 40px;" onChange="$('#demo_cover_pic').css('border-color',this.value);">
                </div>
            </div>

            <div class="row" style="margin: 10px 0px;">
                <div class="col-md-3">
                    <label>
                        <{$smarty.const._MD_TADNEWS_NEWSPIC}>
                    </label>
                    <select class="form-select" name="pic_css[background_repeat]" onChange="$('#demo_cover_pic').css('background-repeat',this.value);">
                        <option value="no-repeat" <{if $pic_css_background_repeat=="no-repeat"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_NO_REPEAT}></option>
                        <option value="repeat" <{if $pic_css_background_repeat=="repeat"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_REPEAT}></option>
                        <option value="repeat-x" <{if $pic_css_background_repeat=="repeat-x"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_X_REPEAT}></option>
                        <option value="repeat-y" <{if $pic_css_background_repeat=="repeat-y"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_Y_REPEAT}></option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label>
                        <{$smarty.const._MD_TADNEWS_NEWSPIC_SHOW}>
                    </label>
                    <select class="form-select" name="pic_css[background_position]" onChange="$('#demo_cover_pic').css('background-position',this.value);">
                        <option value="left top" <{if $pic_css_background_position=="left top"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_LEFT_TOP}></option>
                        <option value="left center" <{if $pic_css_background_position=="left center"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_LEFT_CENTER}></option>
                        <option value="left bottom" <{if $pic_css_background_position=="left bottom"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_LEFT_BOTTOM}></option>
                        <option value="right top" <{if $pic_css_background_position=="right top"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_RIGHT_TOP}></option>
                        <option value="right center" <{if $pic_css_background_position=="right center"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_RIGHT_CENTER}></option>
                        <option value="right bottom" <{if $pic_css_background_position=="right bottom"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_RIGHT_BOTTOM}></option>
                        <option value="center top" <{if $pic_css_background_position=="center top"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_CENTER_TOP}></option>
                        <option value="center center" <{if $pic_css_background_position=="center center"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_CENTER_CENTER}></option>
                        <option value="center bottom" <{if $pic_css_background_position=="center bottom"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_CENTER_BOTTOM}></option>
                    </select>
                </div>

                <div class="col-md-5">
                    <label>
                        <{$smarty.const._MD_TADNEWS_NEWSPIC_AND}>
                    </label>
                    <select class="form-select" name="pic_css[background_size]" onChange="$('#demo_cover_pic').css('background-size',this.value);">
                        <option value="" <{if $pic_css_background_size==""}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_NO_RESIZE}></option>
                        <option value="contain" <{if $pic_css_background_size=="contain"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_CONTAIN}></option>
                        <option value="cover" <{if $pic_css_background_size=="cover"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_COVER}></option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label>
                        <{$smarty.const._MD_TADNEWS_NEWSPIC_MARGIN}>
                    </label>
                    <input type="text" name="pic_css[margin]" value="<{$pic_css_margin|default:''}>" class="form-control" onChange="$('#demo_cover_pic').css('margin',this.value + 'px');" style="width: 80%; display: inline;"> px
                </div>
            </div>
        </div>

        <div class="row" style="margin: 10px 0px;">
            <div class="col-md-12">
                <div class="alert alert-success">
                    <div id="demo_cover_pic" style="color:transparent; background-image: url('<{$pic|default:''}>');<{$pic_css|default:''}>"><{$pic_css|default:''}></div>
                    <{$smarty.const._MD_TADNEWS_NEWSPIC_DEMO}>
                </div>
            </div>
        </div>
    </div>
</div>