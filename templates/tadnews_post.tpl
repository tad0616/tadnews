<{$jquery}>
<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/jqueryCookie/jquery.cookie.js"></script>
<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/multiple-file-upload/jquery.MultiFile.js"></script>
<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/mColorPicker/javascripts/mColorPicker.js" charset="UTF-8"></script>

<{$toolbar}>
<{$formValidator_code}>

<script type="text/javascript">
  $(document).ready(function() {

    $('#color').mColorPicker({
      imageFolder: "<{$xoops_url}>/modules/tadtools/mColorPicker/images/"
    });


    <{if $cate.not_news=='1'}>
      $("#add_page").show();
      $("#add_news").hide();
      $("#show_input_form").hide();
      $("#page_ncsn").attr("disabled",false);
      $("#page_title_input").attr("disabled",false);
      $("#news_ncsn").attr("disabled",true);
      $("#news_title_input").attr("disabled",true);
      $("#new_page_cate_input").attr("disabled",false);
      $("#setup_form").hide();
      $("#page_setup_form").show();
      $("#upform").attr("disabled",true);
      $("#page_upform").attr("disabled",false);
    <{else}>
      $("#add_page").hide();
      $("#add_news").show();
      $("#show_input_form").show();
      $("#page_ncsn").attr("disabled",true);
      $("#page_title_input").attr("disabled",true);
      $("#news_ncsn").attr("disabled",false);
      $("#news_title_input").attr("disabled",false);
      $("#new_page_cate_input").attr("disabled",true);
      $("#setup_form").show();
      $("#page_setup_form").hide();
      $("#upform").attr("disabled",false);
      $("#page_upform").attr("disabled",true);
    <{/if}>

    <{if $nsn==""}>
      $("#kind").change(function() {
        if ($("#kind").val()=="page") {
          $("#add_page").show();
          $("#add_news").hide();
          $("#show_input_form").hide();
          $("#page_ncsn").attr("disabled",false);
          $("#page_title_input").attr("disabled",false);
          $("#news_ncsn").attr("disabled",true);
          $("#news_title_input").attr("disabled",true);
          $("#new_page_cate_input").attr("disabled",false);
          $("#setup_form").hide();
          $("#page_setup_form").show();
          $("#upform").attr("disabled",true);
          $("#page_upform").attr("disabled",false);
        }else{
          $("#add_page").hide();
          $("#add_news").show();
          $("#show_input_form").show();
          $("#page_ncsn").attr("disabled",true);
          $("#page_title_input").attr("disabled",true);
          $("#news_ncsn").attr("disabled",false);
          $("#news_title_input").attr("disabled",false);
          $("#new_page_cate_input").attr("disabled",true);
          $("#setup_form").show();
          $("#page_setup_form").hide();
          $("#upform").attr("disabled",false);
          $("#page_upform").attr("disabled",true);
        }
      });
    <{/if}>
    var $tabs = $("#<{$jquery_tabs_id}>").tabs({ cookie: { expires: 30 } });

    <{if $use_pic_css==""}>
      $("#pic_css_set").hide();
    <{else}>
      $("#pic_css_set").show();
    <{/if}>

    $("#pic_css").change(function(){
      if($("#pic_css").val()=="true"){
        $("#pic_css_set").show();
        $("#demo_cover_pic").attr("style","background-image: url('<{$pic}>');width:200px; height:150px; border:1px solid #909090; background-position:center center; background-repeat:no-repeat; background-size:cover; float:right; margin:4px;");
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
      <div class="col-md-2">
        <div style="background-image: url('<{$pic}>'); background-size: cover; width: 120px; height: 90px;"></div>
      </div>
    <{/if}>
    <div class="col-md-10">
    <{$smarty.const._TAD_EDIT}><{if $cate.not_news=='1'}><{$smarty.const._MD_TADNEWS_KIND_PAGE}><{else}><{$smarty.const._MD_TADNEWS_KIND_NEWS}><{/if}>
    </div>
  </div>
  <{/if}>
</h1>

<form action="<{$action}>" method="post" id="myForm" name="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
  <{if $nsn==""}>
    <div class="form-group">
      <label class="col-md-2 control-label">
        <{$smarty.const._MD_TADNEWS_KIND}>
      </label>
      <div class="col-md-4">
        <select name="kind" id="kind" class="form-control">
          <option value="news"<{if $cate.not_news!='1'}> selected<{/if}>><{$smarty.const._MD_TADNEWS_KIND_NEWS}></option>
          <option value="page"<{if $cate.not_news=='1'}> selected<{/if}>><{$smarty.const._MD_TADNEWS_KIND_PAGE}></option>
        </select>
      </div>
    </div>
  <{/if}>

  <div class="form-group">
    <div id="add_news">
      <div class="col-md-2">
        <select name="ncsn" id="news_ncsn" class="form-control">
          <{$news_cate_select}>
        </select>
      </div>

      <{if $creat_cate_tool}>
        <div class="col-md-3">
          <input type='text' name='new_cate' id='new_cate_input' class='form-control' value='<{$new_cate_input}>' placeholder='<{$creat_new_cate}>'>
        </div>
      <{/if}>

      <div class="col-md-2">
        <{$prefix_tag_menu}>
      </div>

      <div class="col-md-<{if $creat_cate_tool}>5<{else}>8<{/if}>">
        <input type="text" name="news_title" id="news_title_input" class="validate[required] form-control" value="<{$news_title}>" placeholder="<{$smarty.const._MD_TADNEWS_NEWS_TITLE}>">
      </div>
    </div>
    <div id="add_page">
      <div class="col-md-2">
        <select name="ncsn" id="page_ncsn" class="form-control">
          <{$page_cate_select}>
        </select>
      </div>
      <{if $creat_cate_tool}>
        <div class="col-md-3">
          <input type='text' name='new_page_cate' id='new_page_cate_input' class='form-control' value='<{$new_cate_input}>' placeholder='<{$creat_new_cate}>'>
        </div>
      <{/if}>
      <div class="col-md-<{if $creat_cate_tool}>7<{else}>10<{/if}>">
        <input type="text" name="news_title" id="page_title_input" class="validate[required] form-control" value="<{$news_title}>" placeholder="<{$smarty.const._MD_TADNEWS_NEWS_TITLE}>">
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="col-md-12">
      <{$editor}>
    </div>
  </div>


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

          <div class="col-md-4">
        	 <label><{$smarty.const._MD_TADNEWS_START_DATE}><{$smarty.const._MD_TADNEWS_FOR}></label>
           <input type="text" value="<{$start_day}>" name="start_day" id="start_day" class="form-control" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" >
          </div>

          <div class="col-md-4">
            <label><{$smarty.const._MD_TADNEWS_END_DATE}><{$smarty.const._MD_TADNEWS_FOR}></label>
            <input type="text" value="<{$end_day}>" name="end_day" id="end_day"  class="form-control" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',startDate:'%y-%M-{%d+14} %H:%m:%s'})">
          </div>

          <div class="col-md-4">
            <{if $use_top_tool=='1'}>
          	  <label class="checkbox">
                <input type="checkbox" name="always_top" id="always_top" value="1" <{$always_top_checked}>>
                <{$smarty.const._MD_TADNEWS_ALWAYS_TOP}>
              </label>
              <span id="top_date_input">
                <input type="text" name="always_top_date"  class="form-control" id="always_top_date"  value="<{$always_top_date}>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',startDate:'%y-%M-{%d+14} %H:%m'})" align="absmiddle">
              </span>
            <{/if}>
          </div>

        </div>
      </div>

      <div id="fragment-2">

        <div class="row">

          <div class="col-md-3">
            <label><{$smarty.const._MD_TADNEWS_CAN_READ_NEWS_GROUP}><{$smarty.const._MD_TADNEWS_FOR}></label>
            <{$enable_group}>
          </div>

          <div class="col-md-3">
            <label><{$smarty.const._MD_TADNEWS_NEWS_HAVE_READ}><{$smarty.const._MD_TADNEWS_FOR}></label>
            <{$have_read_group}>
          </div>

          <div class="col-md-3">
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

          <div class="col-md-3">
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
          <div class="col-md-6">
            <label><{$smarty.const._MD_TADNEWS_NEWS_PIC}></label>
            <input id="upfile2" type="file" name="upfile2" multiple>
          </div>
        </div>

        <div id="show_pic_css_setup">
          <div class="row">
            <div class="col-md-6">
              <label>
                <{$smarty.const._MD_TADNEWS_ENABLE_NEWSPIC}>
              </label>
              <select class="form-control" name="pic_css[use_pic_css]" id="pic_css">
                <option value="" <{if $use_pic_css==""}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_ENABLE_NEWSPIC_NO}></option>
                <option value="true" <{if $use_pic_css!=""}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_ENABLE_NEWSPIC_YES}></option>
              </select>
            </div>
          </div>

          <div id="pic_css_set">
            <div class="alert alert-info">
              <div class="row" style="margin: 20px 0px;">
                <div class="col-md-3">
                  <label>
                    <{$smarty.const._MD_TADNEWS_NEWSPIC_WIDTH}>
                  </label>
                  <div>
                    <input type="text" name="pic_css[width]" value="<{$pic_css_width}>" class="form-control" onChange="$('#demo_cover_pic').css('width',this.value+'px');" style="width: 60px; display: inline;"> x
                    <input type="text" name="pic_css[height]" value="<{$pic_css_height}>" class="form-control" onChange="$('#demo_cover_pic').css('height',this.value+'px');" style="width: 60px; display: inline;"> px
                  </div>
                </div>

                <div class="col-md-2">
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

                <div class="col-md-3">
                  <label>
                    <{$smarty.const._MD_TADNEWS_NEWSPIC_BORDER}><{$smarty.const._MD_TADNEWS_NEWSPIC_BORDER_WIDTH}>
                  </label>
                  <input type="text" name="pic_css[border_width]" value="<{$pic_css_border_width}>" class="form-control" onChange="$('#demo_cover_pic').css('border-width',this.value+'px');" style="width: 80%; display: inline;"> px
                </div>

                <div class="col-md-3">
                  <label>
                    <{$smarty.const._MD_TADNEWS_NEWSPIC_FLOAT}>
                  </label>
                  <select class="form-control" name="pic_css[float]" onChange="$('#demo_cover_pic').css('float',this.value);">
                    <option value="left" <{if $pic_css_float=="left"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_FLOAT_LEFT}></option>
                    <option value="right" <{if $pic_css_float=="right"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_FLOAT_RIGHT}></option>
                    <option value="none" <{if $pic_css_float=="none"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_FLOAT_NONE}></option>
                  </select>
                </div>

                <div class="col-md-1">
                  <label>
                    <{$smarty.const._MD_TADNEWS_NEWSPIC_BORDER_COLOR}>
                  </label>
                  <input type="text" name="pic_css[border_color]" id="border_color" value="<{$pic_css_border_color}>" data-text="hidden" data-hex="true" style="height: 40px; width: 40px;" onChange="$('#demo_cover_pic').css('border-color',this.value);" />
                </div>
              </div>

              <div class="row" style="margin: 10px 0px;">
                <div class="col-md-3">
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

                <div class="col-md-2">
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

                <div class="col-md-5">
                  <label>
                    <{$smarty.const._MD_TADNEWS_NEWSPIC_AND}>
                  </label>
                  <select class="form-control" name="pic_css[background_size]" onChange="$('#demo_cover_pic').css('background-size',this.value);">
                    <option value="" <{if $pic_css_background_size==""}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_NO_RESIZE}></option>
                    <option value="contain" <{if $pic_css_background_size=="contain"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_CONTAIN}></option>
                    <option value="cover" <{if $pic_css_background_size=="cover"}>selected="selected"<{/if}>><{$smarty.const._MD_TADNEWS_NEWSPIC_COVER}></option>
                  </select>
                </div>


                <div class="col-md-2">
                  <label>
                    <{$smarty.const._MD_TADNEWS_NEWSPIC_MARGIN}>
                  </label>
                  <input type="text" name="pic_css[margin]" value="<{$pic_css_margin}>" class="form-control" onChange="$('#demo_cover_pic').css('margin',this.value + 'px');" style="width: 80%; display: inline;"> px
                </div>
              </div>
            </div>

            <div class="row" style="margin: 10px 0px;">
              <div class="col-md-12">
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
        <div class="row">
          <div class="col-md-12">
            <label><{$smarty.const._MD_TADNEWS_NEWS_FILES}></label>
            <{$upform}>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div id="page_setup_form" style="display: none;">
    <div class="form-group">
      <div class="col-md-2">
        <label><{$smarty.const._MD_TADNEWS_NEWS_FILES}></label>
      </div>
      <div class="col-md-10">
        <{$page_upform}>
      </div>
    </div>
  </div>


  <div class="row" style="margin: 30px 0px;">
    <div class="col-md-12 text-center">
      <input type="hidden" name="nsn" value="<{$nsn}>">
      <input type="hidden" name="uid" value="<{$uid}>">
      <input type="hidden" name="op" value="<{$op}>">

      <{if $cate.not_news!='1'}>
        <button class="btn btn-success" type="button" id="show_input_form"><{$smarty.const._MD_TADNEWS_ADV_SETUP}></button>
      <{/if}>
      <button class="btn btn-primary" type="submit"><{$smarty.const._MD_TADNEWS_SAVE_NEWS}></button>
    </div>
  </div>
</form>