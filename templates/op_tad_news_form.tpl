<script type="text/javascript">
    $(document).ready(function() {
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
            $("#page_mode").prop("disabled",false);
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
            $("#page_mode").prop("disabled",true);
        <{/if}>

        <{if !$nsn}>
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
                    $("#page_mode").prop("disabled",false);
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
                    $("#page_mode").prop("disabled",true);
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
            $("#news_title_input_col").removeClass("col-md-7").addClass("col-md-5");
        });

        $("#new_page_folder_icon").click(function() {
            $("#new_page_folder_icon").hide();
            $("#new_page_folder_col").show();
            $("<option value='0'>/</option>").prependTo("#page_ncsn");
            $("#page_title_input_col").removeClass("col-md-9").addClass("col-md-7");
        });

        var $tabs = $("#<{$jquery_tabs_id|default:''}>").tabs({ cookie: { expires: 30 } });

        <{if $use_pic_css==""}>
            $("#pic_css_set").hide();
        <{else}>
            $("#pic_css_set").show();
        <{/if}>

        $("#pic_css").change(function(){
            if($("#pic_css").val()=="true"){
                $("#pic_css_set").show();
                $("#demo_cover_pic").prop("style","background-image: url('<{$pic|default:''}>');width:200px; height:150px; border:1px solid #909090; background-position:center center; background-repeat:no-repeat; background-size:cover; float:right; margin:4px;");
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
    <{if !$nsn}>
        <{$smarty.const._MD_TADNEWS_ADD_NEWS}>
    <{else}>
        <div class="row">
            <{if $pic|default:false}>
                <div class="col-md-2">
                    <div style="background-image: url('<{$pic|default:''}>'); background-size: cover; width: 120px; height: 90px;"></div>
                </div>
            <{/if}>
            <div class="col-md-10">
                <{$smarty.const._TAD_EDIT}>
                <{if $cate.not_news=='1'}>
                    <{$smarty.const._MD_TADNEWS_KIND_PAGE}>
                <{else}>
                    <{$smarty.const._MD_TADNEWS_KIND_NEWS}>
                <{/if}>
            </div>
        </div>
    <{/if}>
</h1>

<form action="<{$action|default:''}>" method="post" id="myForm" name="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
    <{if !$nsn}>
        <div class="form-group row mb-3">
            <label class="col-md-3 control-label col-form-label text-md-right text-md-end">
                <{$smarty.const._MD_TADNEWS_KIND}>
            </label>
            <div class="col-md-4">
                <select name="kind" id="kind" class="form-select">
                    <option value="news"<{if $ncsn and $cate.not_news!='1'}> selected<{/if}>><{$smarty.const._MD_TADNEWS_KIND_NEWS}></option>
                    <{if $page_cate_select || $creat_cate_tool}>
                        <option value="page"<{if $ncsn and  $cate.not_news=='1'}> selected<{/if}>><{$smarty.const._MD_TADNEWS_KIND_PAGE}></option>
                    <{/if}>
                </select>
            </div>
            <div class="col-md-5" id="tab_mode_checkbox">
                <label for="tab_mode" class="checkbox">
                <input type="checkbox" name="tab_mode" id="tab_mode" value="1"><{$smarty.const._MD_TADNEWS_USE_TAB_MODE}>
                </label>
            </div>
        </div>
    <{else}>
        <{if $tab_arr|default:false}>
        <input type="hidden" name="tab_mode" value="1">
        <{/if}>
    <{/if}>

    <div id="add_news">
        <div class="form-group row mb-3">
            <{if $news_cate_select|default:false}>
                <div class="col-md-2">
                    <select name="ncsn" id="news_ncsn" class="form-select">
                        <{$news_cate_select|default:''}>
                    </select>
                </div>
                <{if $creat_cate_tool|default:false}>
                    <div class="col-md-1" id="new_folder_icon">
                        <img src="images/new_folder.png" alt="<{$smarty.const._TADNEWS_CREAT_FIRST_CATE}>" title="<{$smarty.const._TADNEWS_CREAT_FIRST_CATE}>" style="cursor: pointer;">
                    </div>
                    <div class="col-md-3" id="new_folder_col" style="display: none;">
                        <input type='text' name='new_cate' id='new_cate_input' class='form-control' value='<{$new_cate_input|default:''}>' placeholder='<{$creat_new_cate|default:''}>'>
                    </div>
                <{/if}>
            <{else}>
                <{if $creat_cate_tool|default:false}>
                    <div class="col-md-3">
                        <input type='text' name='new_cate' id='new_cate_input' class='validate[required] form-control' value='<{$new_cate_input|default:''}>' placeholder='<{$creat_new_cate|default:''}>'>
                    </div>
                <{/if}>
            <{/if}>

            <div class="col-md-2">
                <{$prefix_tag_menu|default:''}>
            </div>

            <div class="col-md-<{if $creat_cate_tool|default:false}>7<{else}>8<{/if}>" id="news_title_input_col">
                <input type="text" name="news_title" id="news_title_input" class="validate[required] form-control" value="<{$news_title|default:''}>" placeholder="<{$smarty.const._MD_TADNEWS_NEWS_TITLE}>">
            </div>
        </div>
    </div>

    <div id="add_page">
        <div class="form-group row mb-3">
            <{if $page_cate_select|default:false}>
                <div class="col-md-2">
                    <select name="ncsn" id="page_ncsn" class="form-select">
                        <{$page_cate_select|default:''}>
                    </select>
                </div>
                <{if $creat_cate_tool|default:false}>
                    <div class="col-md-1" id="new_page_folder_icon">
                        <img src="images/new_folder.png" alt="<{$smarty.const._TADNEWS_CREAT_FIRST_CATE}>" title="<{$smarty.const._TADNEWS_CREAT_FIRST_CATE}>" style="cursor: pointer;">
                    </div>
                    <div class="col-md-3" id="new_page_folder_col" style="display: none;">
                        <input type='text' name='new_page_cate' id='new_page_cate_input' class='form-control' value='<{$new_cate_input|default:''}>' placeholder='<{$creat_new_cate|default:''}>'>
                    </div>
                <{/if}>
            <{else}>
                <{if $creat_cate_tool|default:false}>
                    <div class="col-md-3" id="new_page_folder_col">
                        <input type='text' name='new_page_cate' id='new_page_cate_input' class='validate[required] form-control' value='<{$new_cate_input|default:''}>' placeholder='<{$creat_new_cate|default:''}>'>
                    </div>
                <{/if}>
            <{/if}>
            <div class="col-md-<{if $creat_cate_tool|default:false}>9<{else}>10<{/if}>" id="page_title_input_col">
                <input type="text" name="news_title" id="page_title_input" class="validate[required] form-control" value="<{$news_title|default:''}>" placeholder="<{$smarty.const._MD_TADNEWS_NEWS_TITLE}>">
            </div>
        </div>
    </div>

    <div id="editor" <{if $tab_arr|default:false}>style="display: none;"<{/if}>>
        <{$editor|default:''}>
    </div>

    <div id="tab_editor" <{if !$tab_arr}>style="display: none;"<{/if}>>
        <div class="input_fields_wrap">
            <h3><{$smarty.const._MD_TADNEWS_CONTENT_AT_TABS_TOP}></h3>
            <{$tab_editor0|default:''}>

            <h3><{$smarty.const._MD_TADNEWS_CONTENT_AT_TABS}></h3>
            <{if $tab_arr|default:false}>
                <{foreach from=$tab_arr.tab_title key=k item=title}>
                    <{if $k >0}>
                        <div class="alert alert-info">
                            <div class="form-group row mb-3">
                                <div class="col-md-8">
                                    <input type="text" name="tab_title[<{$k|default:''}>]" class="form-control" placeholder="<{$smarty.const._MD_TADNEWS_TAB_TITLE|sprintf:$k}>" value="<{$title|default:''}>">
                                </div>
                                <div class="col-md-4">
                                    <a href="javascript:del_page_tab('<{$k|default:''}>')" class="btn btn-sm btn-xs btn-danger"><i class="fa fa-times"></i> <{$smarty.const._MD_TADNEWS_DELETE_TAB|sprintf:$title}></a>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-md-12">
                                    <{$tab_arr.tab_editor.$k}>
                                </div>
                            </div>
                        </div>
                    <{/if}>
                <{/foreach}>
            <{else}>
                <div class="alert alert-warning">
                    <div class="form-group row mb-3">
                        <div class="col-md-12">
                        <input type="text" name="tab_title[1]" class="form-control" placeholder="<{$smarty.const._MD_TADNEWS_TAB_TITLE|sprintf:1}>" value="<{$tab_arr.tab_title.1}>">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-md-12">
                            <{$tab_editor|default:''}>
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
            <{if $tab_arr.tab_title|default:false}>
            var x = <{$tab_arr.tab_title|@count}>-1; //初始值
            <{else}>
            var x = 1; //initlal text box count
            <{/if}>
            $(add_button).click(function(e){ //on add input button click
                e.preventDefault();
                if(x < max_fields){ //max input box allowed
                    x++; //text box increment
                    var editorId = 'editor_' +x;
                    $(wrapper).append('<div class="alert alert-success"><div class="form-group row mb-3"><div class="col-md-12"><input type="text" name="tab_title['+x+']" class="form-control" placeholder="<{$smarty.const._MD_TADNEWS_TAB_TITLE1}> '+x+' <{$smarty.const._MD_TADNEWS_TAB_TITLE2}>"></div></div><div class="form-group row mb-3"><div class="col-md-12"><textarea id="'+editorId+'" class="ckeditor" name="tab_content['+x+']"></textarea><a href="#" class="remove_field"><{$smarty.const._MD_TADNEWS_DEL_TAB}> '+x+'</a></div></div></div>'); //add input box

                    CKEDITOR.replace(editorId, { height: 300 ,
                    toolbar : 'my' ,
                    contentsCss : ['<{$xoops_url}>/modules/tadtools/bootstrap<{$smarty.session.bootstrap}>/css/bootstrap.css','<{$xoops_url}>/media/font-awesome/css/font-awesome.min.css'],
                    extraPlugins: 'editorplaceholder,pasteUploadImage,sourcearea,font,syntaxhighlight,dialog,eqneditor,quicktable,imagerotate,fakeobjects,widget,lineutils,widgetbootstrap,widgettemplatemenu,pagebreak,fontawesome,prism,codesnippet,undo,autoembed,autolink,clipboard,toolbar,button,dialogui,notification,textmatch,embed,embedbase,widgetselection,notificationaggregator,embedsemantic,panel,floatpanel,menu',
                    filebrowserBrowseUrl : '<{$xoops_url}>/modules/tadtools/elFinder/elfinder.php?type=file&mod_dir=tadnews',
                    filebrowserImageBrowseUrl : '<{$xoops_url}>/modules/tadtools/elFinder/elfinder.php?type=image&mod_dir=tadnews',
                    filebrowserFlashBrowseUrl : '<{$xoops_url}>/modules/tadtools/elFinder/elfinder.php?type=flash&mod_dir=tadnews',
                    qtRows: 10, // Count of rows
                    qtColumns: 10, // Count of columns
                    qtBorder: '1', // Border of inserted table
                    qtWidth: '100%', // Width of inserted table
                    qtStyle: { 'border-collapse' : 'collapse' },
                    qtClass: 'table table-bordered table-hover table-sm table-condensed', // Class of table
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
                $.post('save_sort.php?op=sort_tabs&nsn=<{$nsn|default:''}>', order, function(theResponse){
                    $('#save_msg').html(theResponse);
                });
            }
            });
        });
    </script>



    <div id="setup_form" style="display: none;">
        <div id="<{$jquery_tabs_id|default:''}>">
            <ul>
                <li><a href="#fragment-1"><span><{$smarty.const._MD_TADNEWS_TIME_TAB}></span></a></li>
                <li><a href="#fragment-2"><span><{$smarty.const._MD_TADNEWS_PRIVILEGE_TAB}></span></a></li>
                <li><a href="#fragment-3"><span><{$smarty.const._MD_TADNEWS_NEWSPIC_TAB}></span></a></li>
                <li><a href="#fragment-4"><span><{$smarty.const._MD_TADNEWS_FILES_TAB}></span></a></li>
            </ul>

            <div id="fragment-1">
                <{include file="$xoops_rootpath/modules/tadnews/templates/fragment1.tpl"}>
            </div>

            <div id="fragment-2">
                <{include file="$xoops_rootpath/modules/tadnews/templates/fragment2.tpl"}>
            </div>

            <div id="fragment-3">
                <{include file="$xoops_rootpath/modules/tadnews/templates/fragment3.tpl"}>
            </div>

            <div id="fragment-4">
                <{include file="$xoops_rootpath/modules/tadnews/templates/fragment4.tpl"}>
            </div>

        </div>
    </div>

    <{if !$nsn or ($nsn and $cate.not_news=='1')}>
    <div id="page_setup_form" style="margin-top:20px;display: none;">
        <div class="form-group row mb-3">
            <div class="col-md-2">
                <label><{$smarty.const._MD_TADNEWS_NEWS_FILES}><{$deny_type|default:''}></label>
            </div>
            <div class="col-md-10">
                <{$page_upform|default:''}>
            </div>
        </div>
    </div>
    <{/if}>


    <div class="row" style="margin: 30px 0px;">
        <div class="col-md-12 text-center">
            <input type="hidden" name="nsn" value="<{$nsn|default:''}>">
            <input type="hidden" name="uid" value="<{$uid|default:''}>">
            <input type="hidden" name="op" value="<{$op|default:''}>">

            <{if $cate.not_news!='1'}>
                <button class="btn btn-success" type="button" id="show_input_form"><{$smarty.const._MD_TADNEWS_ADV_SETUP}></button>
            <{else}>
                <input type="hidden" name="page_mode" id="page_mode" value="not_news">
            <{/if}>
            <{$XOOPS_TOKEN|default:''}>
            <button class="btn btn-primary" type="submit"><{$smarty.const._MD_TADNEWS_SAVE_NEWS}></button>

            <{if $nsn && $uid!=$now_uid}>
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="same_uid" value="1"> <{$smarty.const._MD_TADNEWS_SAME_UID}>
                    </label>
                </div>
            <{/if}>
        </div>
    </div>
</form>