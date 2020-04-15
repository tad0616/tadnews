<{$jquery}>
<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/jqueryCookie/jquery.cookie.js"></script>
<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/multiple-file-upload/jquery.MultiFile.js"></script>
<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/mColorPicker/javascripts/mColorPicker.js" charset="UTF-8"></script>

<{$toolbar}>

<script type="text/javascript">
    $(document).ready(function() {

        $('#color').mColorPicker({
            imageFolder: "<{$xoops_url}>/modules/tadtools/mColorPicker/images/"
        });

        <{if $cate.not_news=='1'}>
            $("#add_page").show();
            $("#add_news").hide();
            $("#show_input_form").hide();
            $("#page_ncsn").prop("disabled",false);
            $("#page_title_input").prop("disabled",false);
            $("#news_ncsn").prop("disabled",true);
            $("#news_title_input").prop("disabled",true);
            $("#new_page_cate_input").prop("disabled",false);
            $("#setup_form").hide();
            $("#page_setup_form").show();
            $("#upform").prop("disabled",true);
            $("#page_upform").prop("disabled",false);
            $("#tab_mode_checkbox").show();
        <{else}>
            $("#add_page").hide();
            $("#add_news").show();
            $("#show_input_form").show();
            $("#page_ncsn").prop("disabled",true);
            $("#page_title_input").prop("disabled",true);
            $("#news_ncsn").prop("disabled",false);
            $("#news_title_input").prop("disabled",false);
            $("#new_page_cate_input").prop("disabled",true);
            $("#setup_form").show();
            $("#page_setup_form").hide();
            $("#upform").prop("disabled",false);
            $("#page_upform").prop("disabled",true);
            $("#tab_mode_checkbox").hide();
        <{/if}>

        <{if $nsn==""}>
            $("#kind").change(function() {
                if ($("#kind").val()=="page") {
                $("#add_page").show();
                $("#add_news").hide();
                $("#show_input_form").hide();
                $("#page_ncsn").prop("disabled",false);
                $("#page_title_input").prop("disabled",false);
                $("#news_ncsn").prop("disabled",true);
                $("#news_title_input").prop("disabled",true);
                $("#new_page_cate_input").prop("disabled",false);
                $("#setup_form").hide();
                $("#page_setup_form").show();
                $("#upform").prop("disabled",true);
                $("#page_upform").prop("disabled",false);
                $("#tab_mode_checkbox").show();
                }else{
                $("#add_page").hide();
                $("#add_news").show();
                $("#show_input_form").show();
                $("#page_ncsn").prop("disabled",true);
                $("#page_title_input").prop("disabled",true);
                $("#news_ncsn").prop("disabled",false);
                $("#news_title_input").prop("disabled",false);
                $("#new_page_cate_input").prop("disabled",true);
                $("#setup_form").show();
                $("#page_setup_form").hide();
                $("#upform").prop("disabled",false);
                $("#page_upform").prop("disabled",true);
                $("#tab_mode_checkbox").hide();
                }
            });
            // $("#tab_editor").hide();
            $("#tab_mode").change(function(){
                if ($("#tab_mode").prop("checked")) {
                $("#tab_editor").show();
                $("#editor").hide();
                }else{
                $("#tab_editor").hide();
                $("#editor").show();
                }
            });

        <{/if}>


        $("#new_folder_icon").click(function() {
            $("#new_folder_icon").hide();
            $("#new_folder_col").show();
            $("<option value='0'>/</option>").prependTo("#news_ncsn");
            $("#news_title_input_col").removeClass("col-sm-7").addClass("col-sm-5");
        });

        $("#new_page_folder_icon").click(function() {
            $("#new_page_folder_icon").hide();
            $("#new_page_folder_col").show();
            $("<option value='0'>/</option>").prependTo("#page_ncsn");
            $("#page_title_input_col").removeClass("col-sm-9").addClass("col-sm-7");
        });

        var $tabs = $("#<{$jquery_tabs_id}>").tabs({ cookie: { expires: 30 } });

        <{if $use_pic_css==""}>
            $("#pic_css_set").hide();
        <{else}>
            $("#pic_css_set").show();
        <{/if}>

        $("#pic_css").change(function(){
            if($("#pic_css").val()=="true"){
                $("#pic_css_set").show();
                $("#demo_cover_pic").prop("style","background-image: url('<{$pic}>');width:200px; height:150px; border:1px solid #909090; background-position:center center; background-repeat:no-repeat; background-size:cover; float:right; margin:4px;");
            }else{
                $("#pic_css_set").hide();
            }
        });

        $("#setup_form").hide();
        <{if $always_top!='1'}>
            $("#top_date_input").hide();
        <{/if}>

        $("#show_input_form").click(function() {
            if ($("#setup_form").is(":visible")) {
                $("#setup_form").slideUp();
            } else{
                $("#setup_form").slideDown();
            }
        });

        $("#always_top").click(function() {
            if ($("#top_date_input").is(":visible")) {
                $("#top_date_input").fadeOut();
            } else{
                $("#top_date_input").fadeIn();
            }
        });

    });

</script>

<h1>
    <{if $nsn==""}>
        <{$smarty.const._MD_TADNEWS_ADD_NEWS}>
    <{else}>
        <div class="row">
            <{if $pic}>
            <div class="col-sm-2">
                <div style="background-image: url('<{$pic}>'); background-size: cover; width: 120px; height: 90px;"></div>
            </div>
            <{/if}>
            <div class="col-sm-10">
                <{$smarty.const._TAD_EDIT}><{if $cate.not_news=='1'}><{$smarty.const._MD_TADNEWS_KIND_PAGE}><{else}><{$smarty.const._MD_TADNEWS_KIND_NEWS}><{/if}>
            </div>
        </div>
    <{/if}>
</h1>

<form action="<{$action}>" method="post" id="myForm" name="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
    <{if $nsn==""}>
        <div class="form-group">
            <label class="col-sm-2 control-label">
                <{$smarty.const._MD_TADNEWS_KIND}>
            </label>
            <div class="col-sm-4">
                <select name="kind" id="kind" class="form-control">
                    <option value="news"<{if $ncsn and $cate.not_news!='1'}> selected<{/if}>><{$smarty.const._MD_TADNEWS_KIND_NEWS}></option>
                <option value="page"<{if $ncsn and  $cate.not_news=='1'}> selected<{/if}>><{$smarty.const._MD_TADNEWS_KIND_PAGE}></option>
                </select>
            </div>
            <div class="col-sm-4" id="tab_mode_checkbox">
                <label for="tab_mode" class="checkbox">
                <input type="checkbox" name="tab_mode" id="tab_mode" value="1"><{$smarty.const._MD_TADNEWS_USE_TAB_MODE}>
                </label>
            </div>
        </div>
    <{else}>
        <{if $tab_arr}>
            <input type="hidden" name="tab_mode" value="1">
        <{/if}>
    <{/if}>




    <div class="form-group">

        <div id="add_news">
        <{if $news_cate_select}>
            <div class="col-sm-2">
            <select name="ncsn" id="news_ncsn" class="form-control">
                <{$news_cate_select}>
            </select>
            </div>
            <{if $creat_cate_tool}>
            <div class="col-sm-1" id="new_folder_icon">
                <img src="images/new_folder.png" alt="<{$smarty.const._TADNEWS_CREAT_FIRST_CATE}>" title="<{$smarty.const._TADNEWS_CREAT_FIRST_CATE}>" style="cursor: pointer;">
            </div>
            <div class="col-sm-3" id="new_folder_col" style="display: none;">
                <input type='text' name='new_cate' id='new_cate_input' class='form-control' value='<{$new_cate_input}>' placeholder='<{$creat_new_cate}>'>
            </div>
            <{/if}>
        <{else}>
            <{if $creat_cate_tool}>
            <div class="col-sm-3">
                <input type='text' name='new_cate' id='new_cate_input' class='validate[required] form-control' value='<{$new_cate_input}>' placeholder='<{$creat_new_cate}>'>
            </div>
            <{/if}>
        <{/if}>

        <div class="col-sm-2">
            <{$prefix_tag_menu}>
        </div>

        <div class="col-sm-<{if $creat_cate_tool}>7<{else}>8<{/if}>" id="news_title_input_col">
            <input type="text" name="news_title" id="news_title_input" class="validate[required] form-control" value="<{$news_title}>" placeholder="<{$smarty.const._MD_TADNEWS_NEWS_TITLE}>">
        </div>
        </div>

        <div id="add_page">
        <{if $page_cate_select}>
            <div class="col-sm-2">
            <select name="ncsn" id="page_ncsn" class="form-control">
                <{$page_cate_select}>
            </select>
            </div>
            <{if $creat_cate_tool}>
            <div class="col-sm-1" id="new_page_folder_icon">
                <img src="images/new_folder.png" alt="<{$smarty.const._TADNEWS_CREAT_FIRST_CATE}>" title="<{$smarty.const._TADNEWS_CREAT_FIRST_CATE}>" style="cursor: pointer;">
            </div>
            <div class="col-sm-3" id="new_page_folder_col" style="display: none;">
                <input type='text' name='new_page_cate' id='new_page_cate_input' class='form-control' value='<{$new_cate_input}>' placeholder='<{$creat_new_cate}>'>
            </div>
            <{/if}>
        <{else}>
            <{if $creat_cate_tool}>
            <div class="col-sm-3" id="new_page_folder_col">
                <input type='text' name='new_page_cate' id='new_page_cate_input' class='validate[required] form-control' value='<{$new_cate_input}>' placeholder='<{$creat_new_cate}>'>
            </div>
            <{/if}>
        <{/if}>
        <div class="col-sm-<{if $creat_cate_tool}>9<{else}>10<{/if}>" id="page_title_input_col">
            <input type="text" name="news_title" id="page_title_input" class="validate[required] form-control" value="<{$news_title}>" placeholder="<{$smarty.const._MD_TADNEWS_NEWS_TITLE}>">
        </div>
        </div>

    </div>

    <div id="editor" <{if $tab_arr}>style="display: none;"<{/if}>>
        <{$editor}>
    </div>

    <div id="tab_editor" <{if !$tab_arr}>style="display: none;"<{/if}>>

        <div class="input_fields_wrap">
            <{if $tab_arr}>
                <{foreach from=$tab_arr.tab_title key=k item=title}>
                    <div class="alert alert-info">
                        <div class="form-group">
                            <div class="col-sm-8">
                                <input type="text" name="tab_title[<{$k}>]" class="form-control" placeholder="<{$smarty.const._MD_TADNEWS_TAB_TITLE|sprintf:$k}>" value="<{$title}>">
                            </div>
                            <div class="col-sm-4">
                                <a href="javascript:del_page_tab('<{$k}>')" class="btn btn-xs btn-danger"><{$smarty.const._MD_TADNEWS_DELETE_TAB|sprintf:$title}></a>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <{$tab_arr.tab_editor.$k}>
                            </div>
                        </div>
                    </div>
                <{/foreach}>
            <{else}>
                <div class="alert alert-warning">
                    <div class="form-group">
                        <div class="col-sm-12">
                        <input type="text" name="tab_title[1]" class="form-control" placeholder="<{$smarty.const._MD_TADNEWS_TAB_TITLE|sprintf:1}>" value="<{$tab_arr.tab_title.1}>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <{$tab_editor}>
                        </div>
                    </div>
                </div>
            <{/if}>
        </div>

        <button id="add_field_button" class="btn btn-primary"><{$smarty.const._MD_TADNEWS_ADD_TAB}></button>
    </div>
    <script>
        $(document).ready(function() {
            var max_fields      = 20; //maximum input boxes allowed
            var wrapper         = $(".input_fields_wrap"); //Fields wrapper
            var add_button      = $("#add_field_button"); //Add button ID
            <{if $k}>
            var x = <{$k}>; //initlal text box count
            <{else}>
            var x = 1; //initlal text box count
            <{/if}>
            $(add_button).click(function(e){ //on add input button click
                e.preventDefault();
                if(x < max_fields){ //max input box allowed
                    x++; //text box increment
                    var editorId = 'editor_' +x;
                    $(wrapper).append('<div class="alert alert-success"><div class="form-group"><div class="col-sm-12"><input type="text" name="tab_title['+x+']" class="form-control" placeholder="<{$smarty.const._MD_TADNEWS_TAB_TITLE1}> '+x+' <{$smarty.const._MD_TADNEWS_TAB_TITLE2}>"></div></div><div class="form-group"><div class="col-sm-12"><textarea id="'+editorId+'" class="ckeditor" name="tab_content['+x+']"></textarea><a href="#" class="remove_field"><{$smarty.const._MD_TADNEWS_DEL_TAB}> '+x+'</a></div></div></div>'); //add input box

                    CKEDITOR.replace(editorId, { height: 300 ,
                    toolbar : 'my' ,
                    contentsCss : ['<{$xoops_url}>/modules/tadtools/bootstrap3/css/bootstrap.css','<{$xoops_url}>/modules/tadtools/css/font-awesome/css/font-awesome.css'],
                    extraPlugins: 'syntaxhighlight,dialog,oembed,eqneditor,quicktable,imagerotate,fakeobjects,widget,lineutils,widgetbootstrap,widgettemplatemenu,pagebreak,fontawesome,prism,codesnippet',
                    filebrowserBrowseUrl : '<{$xoops_url}>/modules/tadtools/elFinder/elfinder.php?type=file&mod_dir=tadnews',
                    filebrowserImageBrowseUrl : '<{$xoops_url}>/modules/tadtools/elFinder/elfinder.php?type=image&mod_dir=tadnews',
                    filebrowserFlashBrowseUrl : '<{$xoops_url}>/modules/tadtools/elFinder/elfinder.php?type=flash&mod_dir=tadnews',
                    filebrowserUploadUrl : '<{$xoops_url}>/modules/tadtools/upload.php?type=file&mod_dir=tadnews',
                    filebrowserImageUploadUrl : '<{$xoops_url}>/modules/tadtools/upload.php?type=image&mod_dir=tadnews',
                    filebrowserFlashUploadUrl : '<{$xoops_url}>/modules/tadtools/upload.php?type=flash&mod_dir=tadnews',
                    qtRows: 10, // Count of rows
                    qtColumns: 10, // Count of columns
                    qtBorder: '1', // Border of inserted table
                    qtWidth: '100%', // Width of inserted table
                    qtStyle: { 'border-collapse' : 'collapse' },
                    qtClass: 'table table-bordered table-hover table-condensed', // Class of table
                    qtCellPadding: '0', // Cell padding table
                    qtCellSpacing: '0', // Cell spacing table
                    qtPreviewBorder: '1px double black', // preview table border
                    qtPreviewSize: '15px', // Preview table cell size
                    qtPreviewBackground: '#c8def4' // preview table background (hover)
                });
                }
            });

            $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
                e.preventDefault(); $(this).parent('div').remove(); x--;
            });
            $('#sort').sortable({ opacity: 0.6, cursor: 'move', update: function() {
                var order = $(this).sortable('serialize');
                $.post('save_sort.php?op=sort_tabs&nsn=<{$nsn}>', order, function(theResponse){
                    $('#save_msg').html(theResponse);
                });
            }
            });
        });
    </script>



    <div id="setup_form" style="display: none;">
        <div id="<{$jquery_tabs_id}>">
        <ul>
            <li><a href="#fragment-1"><span><{$smarty.const._MD_TADNEWS_TIME_TAB}></span></a></li>
            <li><a href="#fragment-2"><span><{$smarty.const._MD_TADNEWS_PRIVILEGE_TAB}></span></a></li>
            <li><a href="#fragment-3"><span><{$smarty.const._MD_TADNEWS_NEWSPIC_TAB}></span></a></li>
            <li><a href="#fragment-4"><span><{$smarty.const._MD_TADNEWS_FILES_TAB}></span></a></li>
        </ul>

        <div id="fragment-1">
            <div class="row">

            <div class="col-sm-4">
                <label><{$smarty.const._MD_TADNEWS_START_DATE}><{$smarty.const._MD_TADNEWS_FOR}></label>
            <input type="text" value="<{$start_day}>" name="start_day" id="start_day" class="form-control" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" >
            </div>

            <div class="col-sm-4">
                <label><{$smarty.const._MD_TADNEWS_END_DATE}><{$smarty.const._MD_TADNEWS_FOR}></label>
                <input type="text" value="<{$end_day}>" name="end_day" id="end_day"  class="form-control" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',startDate:'%y-%M-{%d+14} %H:%m:%s'})">
            </div>

            <div class="col-sm-4">
                <{if $use_top_tool=='1'}>
                <label class="checkbox">
                    <input type="checkbox" name="always_top" id="always_top" value="1" <{$always_top_checked}>>
                    <{$smarty.const._MD_TADNEWS_ALWAYS_TOP}>
                </label>
                <span id="top_date_input">
                    <input type="text" name="always_top_date"  class="form-control" id="always_top_date"  value="<{$always_top_date}>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',startDate:'%y-%M-{%d+7} %H:%m',maxDate:'%y-%M-{%d+<{$top_max_day}>} %H:%m'})" align="absmiddle">
                </span>
                <{/if}>
            </div>

            </div>
        </div>

        <div id="fragment-2">

            <div class="row">

            <div class="col-sm-3">
                <label><{$smarty.const._MD_TADNEWS_CAN_READ_NEWS_GROUP}><{$smarty.const._MD_TADNEWS_FOR}></label>
                <{$enable_group}>
            </div>

            <div class="col-sm-3">
                <label><{$smarty.const._MD_TADNEWS_NEWS_HAVE_READ}><{$smarty.const._MD_TADNEWS_FOR}></label>
                <{$have_read_group}>
            </div>

            <div class="col-sm-3">
                <label>
                <{$smarty.const._MD_TADNEWS_NEWS_ENABLE}><{$smarty.const._MD_TADNEWS_FOR}>
                </label>
                <label class="radio">
                <input type="radio" name="enable" value="1" id="enable1" <{$enable_checked1}>><{$smarty.const._MD_TADNEWS_NEWS_ENABLE_OK}>
                </label>
                <label class="radio">
                <input type="radio" name="enable" value="0" id="enable0" <{$enable_checked0}>><{$smarty.const._TADNEWS_NEWS_UNABLE}>
                </label>
            </div>

            <div class="col-sm-3">
                <div class="row">
                <label>
                    <{$smarty.const._MD_TADNEWS_NEWS_PASSWD}><{$smarty.const._MD_TADNEWS_FOR}>
                </label>
                <input type="text" name="passwd" id="passwd"  class="form-control" value="<{$passwd}>">
                </div>
            </div>
            </div>
        </div>

        <div id="fragment-3">
            <script type="text/javascript" src="<{$xoops_url}>/modules/tadnews/class/jquery.upload-1.0.2.min.js"></script>
            <{if $pic}>
            <script type="text/javascript">
                d = new Date();
                $(function() {
                $('#show_pic_css_setup').show();
                $('#upfile2').change(function() {
                    $(this).upload('demo_upload.php',{op:'upload' , nsn:'<{$nsn}>'}, function(res) {
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
                    $(this).upload('demo_upload.php',{op:'upload' , nsn:'<{$nsn}>'}, function(res) {
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

            <input type="hidden" name="files_sn" id="files_sn" value="<{$files_sn}>">

            <div class="row">
            <div class="col-sm-6">
                <label><{$smarty.const._MD_TADNEWS_NEWS_PIC}></label>
                <input id="upfile2" type="file" name="upfile2" multiple>
            </div>
            </div>

            <div id="show_pic_css_setup">
            <div class="row">
                <div class="col-sm-6">
                <label>
                    <{$smarty.const._MD_TADNEWS_ENABLE_NEWSPIC}>
                </label>
                <select class="form-control" name="pic_css[use_pic_css]" id="pic_css">
                    <option value="" <{if $use_pic_css==""}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_ENABLE_NEWSPIC_NO}></option>
                    <option value="true" <{if $use_pic_css!=""}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_ENABLE_NEWSPIC_YES}></option>
                </select>
                </div>
                <div class="col-sm-6">
                <{if $pic}>
                <a href="post.php?op=delete_cover&nsn=<{$nsn}>"><{$smarty.const._TAD_DEL}><{$smarty.const._MD_TADNEWS_NEWSPIC_TAB}></a>
                <{/if}>
                </div>
            </div>

            <div id="pic_css_set">
                <div class="alert alert-info">
                <div class="row" style="margin: 20px 0px;">
                    <div class="col-sm-3">
                    <label>
                        <{$smarty.const._MD_TADNEWS_NEWSPIC_WIDTH}>
                    </label>
                    <div>
                        <input type="text" name="pic_css[width]" value="<{$pic_css_width}>" class="form-control" onChange="$('#demo_cover_pic').css('width',this.value+'px');" style="width: 60px; display: inline;"> x
                        <input type="text" name="pic_css[height]" value="<{$pic_css_height}>" class="form-control" onChange="$('#demo_cover_pic').css('height',this.value+'px');" style="width: 60px; display: inline;"> px
                    </div>
                    </div>

                    <div class="col-sm-2">
                    <label>
                        <{$smarty.const._MD_TADNEWS_NEWSPIC_BORDER_STYTLE}>
                    </label>
                    <select class="form-control" name="pic_css[border_style]" onChange="$('#demo_cover_pic').css('border-style',this.value);">

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

                    <div class="col-sm-3">
                    <label>
                        <{$smarty.const._MD_TADNEWS_NEWSPIC_BORDER}><{$smarty.const._MD_TADNEWS_NEWSPIC_BORDER_WIDTH}>
                    </label>
                    <input type="text" name="pic_css[border_width]" value="<{$pic_css_border_width}>" class="form-control" onChange="$('#demo_cover_pic').css('border-width',this.value+'px');" style="width: 80%; display: inline;"> px
                    </div>

                    <div class="col-sm-3">
                    <label>
                        <{$smarty.const._MD_TADNEWS_NEWSPIC_FLOAT}>
                    </label>
                    <select class="form-control" name="pic_css[float]" onChange="$('#demo_cover_pic').css('float',this.value);">
                        <option value="left" <{if $pic_css_float=="left"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_FLOAT_LEFT}></option>
                        <option value="right" <{if $pic_css_float=="right"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_FLOAT_RIGHT}></option>
                        <option value="none" <{if $pic_css_float=="none"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_FLOAT_NONE}></option>
                    </select>
                    </div>

                    <div class="col-sm-1">
                    <label>
                        <{$smarty.const._MD_TADNEWS_NEWSPIC_BORDER_COLOR}>
                    </label>
                    <input type="text" name="pic_css[border_color]" id="border_color" value="<{$pic_css_border_color}>" data-text="hidden" data-hex="true" style="height: 40px; width: 40px;" onChange="$('#demo_cover_pic').css('border-color',this.value);">
                    </div>
                </div>

                <div class="row" style="margin: 10px 0px;">
                    <div class="col-sm-3">
                    <label>
                        <{$smarty.const._MD_TADNEWS_NEWSPIC}>
                    </label>
                    <select class="form-control" name="pic_css[background_repeat]" onChange="$('#demo_cover_pic').css('background-repeat',this.value);">
                        <option value="no-repeat" <{if $pic_css_background_repeat=="no-repeat"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_NO_REPEAT}></option>
                        <option value="repeat" <{if $pic_css_background_repeat=="repeat"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_REPEAT}></option>
                        <option value="repeat-x" <{if $pic_css_background_repeat=="repeat-x"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_X_REPEAT}></option>
                        <option value="repeat-y" <{if $pic_css_background_repeat=="repeat-y"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_Y_REPEAT}></option>
                    </select>
                    </div>

                    <div class="col-sm-2">
                    <label>
                        <{$smarty.const._MD_TADNEWS_NEWSPIC_SHOW}>
                    </label>
                    <select class="form-control" name="pic_css[background_position]" onChange="$('#demo_cover_pic').css('background-position',this.value);">
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

                    <div class="col-sm-5">
                    <label>
                        <{$smarty.const._MD_TADNEWS_NEWSPIC_AND}>
                    </label>
                    <select class="form-control" name="pic_css[background_size]" onChange="$('#demo_cover_pic').css('background-size',this.value);">
                        <option value="" <{if $pic_css_background_size==""}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_NO_RESIZE}></option>
                        <option value="contain" <{if $pic_css_background_size=="contain"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_CONTAIN}></option>
                        <option value="cover" <{if $pic_css_background_size=="cover"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_COVER}></option>
                    </select>
                    </div>


                    <div class="col-sm-2">
                    <label>
                        <{$smarty.const._MD_TADNEWS_NEWSPIC_MARGIN}>
                    </label>
                    <input type="text" name="pic_css[margin]" value="<{$pic_css_margin}>" class="form-control" onChange="$('#demo_cover_pic').css('margin',this.value + 'px');" style="width: 80%; display: inline;"> px
                    </div>
                </div>
                </div>

                <div class="row" style="margin: 10px 0px;">
                <div class="col-sm-12">
                    <div class="well">
                    <div id="demo_cover_pic" style="background-image: url('<{$pic}>');<{$pic_css}>" title="<{$pic_css}>"></div>
                    <{$smarty.const._MD_TADNEWS_NEWSPIC_DEMO}>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>

        <div id="fragment-4">
            <label><{$smarty.const._MD_TADNEWS_NEWS_FILES}><{$deny_type}></label>
            <{$upform}>
        </div>

        </div>
    </div>

    <{if !$nsn or ($nsn and $cate.not_news=='1')}>
    <div id="page_setup_form" style="margin-top:20px;display: none;">
        <div class="form-group">
        <div class="col-sm-2">
            <label><{$smarty.const._MD_TADNEWS_NEWS_FILES}><{$deny_type}></label>
        </div>
        <div class="col-sm-10">
            <{$page_upform}>
        </div>
        </div>
    </div>
    <{/if}>


    <div class="row" style="margin: 30px 0px;">
        <div class="col-sm-12 text-center">
        <input type="hidden" name="nsn" value="<{$nsn}>">
        <input type="hidden" name="uid" value="<{$uid}>">
        <input type="hidden" name="op" value="<{$op}>">

        <{if $cate.not_news!='1'}>
            <button class="btn btn-success" type="button" id="show_input_form"><{$smarty.const._MD_TADNEWS_ADV_SETUP}></button>
        <{/if}>
        <{$XOOPS_TOKEN}>
        <button class="btn btn-primary" type="submit"><{$smarty.const._MD_TADNEWS_SAVE_NEWS}></button>
        </div>
    </div>
</form>