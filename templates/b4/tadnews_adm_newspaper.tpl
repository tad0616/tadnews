<div class="container-fluid">

  <{if $op=="creat_newspaper" or $op=="modify"}>
    <script type="text/javascript" src="../class/nicEdit.js"></script>
    <script type="text/javascript">
      //bkLib.onDomLoaded(function() { nicEditors.allTextAreas()});
      bkLib.onDomLoaded(function() {
        new nicEditor({fullPanel : true}).panelInstance('head');
        new nicEditor({fullPanel : true}).panelInstance('foot');
      });
    </script>

    <h2><{$smarty.const._MA_TADNEWS_NP_STEP1}></h2>

    <form action="newspaper.php" method="post" id="myForm" role="form">
      <div class="form-group row">
        <label for="themes">
          <{$smarty.const._MA_TADNEWS_NP_THEMES}>
        </label>
        <{$nps_theme}>
      </div>


      <div class="form-group row">
        <label for="title"><{$smarty.const._MA_TADNEWS_NP_TITLE}></label>
        <input type="text" name="title" id="title" value="<{$np_title}>" class="form-control">
      </div>

      <div class="form-group row">
        <label for="head"><{$smarty.const._MA_TADNEWS_NP_CONTENT_HEAD}></label>
        <textarea name="head" id="head" class="form-control"><{$head}></textarea>
        <div class="alert alert-default"><{$smarty.const._MA_TADNEWS_NP_CONTENT_HEAD_DESC}></div>
      </div>

      <div class="form-group row">
        <label for="foot"><{$smarty.const._MA_TADNEWS_NP_CONTENT_FOOT}></label>
        <textarea name="foot" id="foot"  class="form-control"><{$foot}></textarea>
        <div class="alert alert-default"><{$smarty.const._MA_TADNEWS_NP_CONTENT_FOOT_DESC}></div>
      </div>

      <div class="form-group row text-center">
          <{$hidden}>
          <input type="hidden" name="op" value="save_newspaper_set">
          <button type="submit" class="btn btn-primary"><{$smarty.const._TADNEWS_NP_SUBMIT}></button>
      </div>
    </form>
  <{elseif $op=="add_newspaper"}>

    <script type="text/javascript" src="../class/tmt_core.js"></script>
    <script type="text/javascript" src="../class/tmt_spry_linkedselect.js"></script>
    <script type="text/javascript">
      function getOptions()
      {
        var x=document.getElementById("destination");
        txt="";
        for (i=0;i<x.length;i++)
          {
          txt=txt + "," + x.options[i].value;
          }
        document.getElementById("all_news").value=txt;
      }
    </script>

    <h2><{$smarty.const._MA_TADNEWS_NP_STEP2}></h2>
    <form action="newspaper.php" method="post" id="myForm" role="form">

      <div class="form-group row">
        <div class="col-sm-11">
          <div class="alert alert-info">
             <{$newspaper_set_title}>
          </div>
        </div>
      </div>



      <div class="form-group row">
        <div class="col-sm-5">
          <select name="repository" id="repository" size="12" multiple="multiple" tmt:linkedselect="true" class="form-control">
            <{$opt}>
          </select>
        </div>
        <div class="col-sm-1 text-center">
          <img src="../images/right.png" onclick="tmt.spry.linkedselect.util.moveOptions('repository', 'destination');getOptions();"><br>
          <img src="../images/left.png" onclick="tmt.spry.linkedselect.util.moveOptions('destination' , 'repository');getOptions();"><br><br>

          <img src="../images/up.png" onclick="tmt.spry.linkedselect.util.moveOptionUp('destination');getOptions();"><br>
          <img src="../images/down.png" onclick="tmt.spry.linkedselect.util.moveOptionDown('destination');getOptions();">
        </div>

        <div class="col-sm-5">
          <select id="destination" size="12" multiple="multiple" tmt:linkedselect="true" class="form-control">
           <{$opt2}>
          </select>
        </div>
      </div>

      <div class="form-group row">
        <div class="col-sm-11 text-center">
          <input type="hidden" name="op" value="save_newspaper">
          <input type="hidden" name="nps_sn" value="<{$nps_sn}>">
          <input type="hidden" name="all_news" id="all_news">
          <button type="submit" class="btn btn-primary"><{$smarty.const._TADNEWS_NP_SUBMIT}></button>
        </div>
      </div>
    </form>
  <{elseif $op=="edit_newspaper"}>

    <h2><{$smarty.const._MA_TADNEWS_NP_STEP3}></h2>
    <form action="newspaper.php" method="post" id="myForm" role="form">
      <div class="form-group row">
        <label class="col-sm-2 col-form-label text-sm-right">
          <{$smarty.const._MA_TADNEWS_NP_SUB_TITLE}>
        </label>
        <div class="col-sm-10">
          <input type="text" name="np_title" class="form-control" value="<{$np_title}>">
        </div>
      </div>

      <div class="form-group row">
        <div class="col-sm-12">
          <{$editor}>
        </div>
      </div>

      <div class="form-group row">
        <div class="col-sm-12 text-center">
          <input type="hidden" name="npsn" value="<{$npsn}>">
          <input type="hidden" name="op" value="save_all">
          <button type="submit" class="btn btn-primary"><{$smarty.const._TADNEWS_SUBMIT}></button>
        </div>
      </div>


    </form>
  <{elseif $op=="sendmail"}>

    <script>
      function delete_tad_news_email_func(email){
        var sure = window.confirm("<{$smarty.const._TADNEWS_SURE_DEL}>");
        if (!sure)  return;
        location.href="newspaper.php?op=delete_tad_news_email_npsn&npsn=<{$npsn}>&nps_sn=<{$nps_sn}>&email=" + email;
      }
    </script>

    <h2><{$smarty.const._MA_TADNEWS_NP_STEP4}></h2>

    <div class="alert alert-info">
      <{$total}>
    </div>

    <{if $log}>
      <form action="newspaper.php" method="post" id="myForm" role="form">
        <table class="table table-condensed">
          <{assign var="i" value=0}>

          <{foreach item=log from=$log }>
            <{if !$i}><tr><{/if}>
              <td>
                <label class="checkbox-inline">
                  <{$log.checkbox}><{$log.email}>
                </label>
              </td>
              <td><{$log.data}></td>
            <{assign var="i" value=$i+1}>
            <{if $i == 2}></tr><{assign var="i" value=0}><{/if}>
          <{/foreach}>
        </table>
        <input type="hidden" name="op" value="send_now">
        <input type="hidden" name="npsn" value="<{$npsn}>">
        <div class="text-center">
          <button type="submit" class="btn btn-primary"><{$smarty.const._MA_TADNEWS_SEND_NOW}></button>
        </div>
      </form>
    <{else}>
      <div class="alert alert-danger">
        <{$no_email}>
      </div>
    <{/if}>
    <iframe title="newspaper preview" src="newspaper.php?op=preview&npsn=<{$npsn}>" style="width: 100%; height: 480px;b order:1px solid gray; clear: both"><{$np_content}></iframe>
  <{elseif $op=="newspaper_email"}>
    <script>
      function delete_tad_news_email_func(email){
      var sure = window.confirm("<{$smarty.const._TADNEWS_SURE_DEL}>");
        if (!sure)  return;
        location.href="newspaper.php?op=delete_tad_news_email&nps_sn=<{$nps_sn}>&g2p=<{$g2p}>&email=" + email;
      }
    </script>

    <h2><{$title}><{$smarty.const._MA_TADNEWS_NP_EMAIL}></h2>

    <div class="row">
      <div class="col-sm-12">
        <a href="newspaper.php?nps_sn=<{$nps_sn}>" class="btn btn-info"><{$back}></a>
      </div>
    </div>

    <{if $log}>
      <div class="text-center">
        <{$bar}>
      </div>
      <table class="table table-striped">
        <{assign var="i" value=0}>

        <{foreach item=log from=$log }>
          <{if !$i}><tr><{/if}>
            <{if $log.edit}>
              <td colspan=5>
                <form action="newspaper.php" method="post">
                <input type="text" name="new_email" value="<{$log.email}>" style="width:100%;background-color:#FFFF99;color:black;"></td><td>
                <input type="hidden" name="old_email" value="<{$log.email}>">
                <input type="hidden" name="op" value="update_email">
                <input type="hidden" name="nps_sn" value="<{$nps_sn}>">
                <input type="hidden" name="g2p" value="<{$g2p}>">
                <input type="submit" value="<{$smarty.const._MA_TADNEWS_SAVE_CATE}>">
                </form>
              </td>
            <{else}>
              <td><{$log.email}></td>
              <td>
              <{if $log.ok=="ok"}>
                <span class="badge badge-info"><{$log.ok}></span>
              <{else}>
                <span class="badge badge-important"><{$log.ok}></span>
              <{/if}>
              </td>
              <td><{$log.order_date}></td>
              <td>
                <a href="newspaper.php?op=newspaper_email&nps_sn=<{$nps_sn}>&memail=<{$log.email}>&g2p=<{$g2p}>" class="btn btn-sm btn-warning"><{$smarty.const._TADNEWS_EDIT}></a>
                <a href="javascript:delete_tad_news_email_func('<{$log.email}>');" class="btn btn-sm btn-danger"><{$smarty.const._TADNEWS_DEL}></a>
              </td>
              <td>
                <{$log.data}>
              </td>
            <{/if}>
          <{assign var="i" value=$i+1}>
          <{if $i == 2}></tr><{assign var="i" value=0}><{/if}>
        <{/foreach}>
      </table>
      <div class="text-center">
        <{$bar}>
      </div>
    <{/if}>
    <form action="newspaper.php" method="post">
      <textarea name="email_import" class="col-sm-12" placeholder="<{$smarty.const._MA_TADNEWS_NP_EMAIL_IMPORT}>"></textarea>
      <input type="hidden" name="nps_sn" value="<{$nps_sn}>">
      <input type="hidden" name="op" value="email_import">
      <div class="text-center" style="margin: 20px 0px;">
        <button type="submit" class="btn btn-primary"><{$smarty.const._MA_TADNEWS_NP_EMAIL_IMPORT}></button>
      </div>
    </form>
  <{elseif $op=="sendmail_log"}>
    <h2><{$title}></h2>

    <div class="row">
      <div class="col-sm-12">
        <a href="newspaper.php?nps_sn=<{$nps_sn}>" class="btn btn-info"><{$back}></a>
      </div>
    </div>

    <{if $empty}>
      <div class="alert alert-danger">
        <{$smarty.const._MA_TADNEWS_EMPTY_LOG}>
      </div>
    <{else}>
      <table class="table table-striped">
        <{assign var="i" value=0}>
        <{foreach item=log from=$log }>
          <td><{$log.email}></td>
          <td><{$log.send_time}></td>
          <td><span class="badge badge-info"><{$log.log}></span></td>
          <{assign var="i" value=$i+1}>
          <{if $i == 2}></tr><{assign var="i" value=0}><{/if}>
        <{/foreach}>
      </table>
    <{/if}>
  <{else}>

    <h2><{$smarty.const._MA_TADNEWS_NP_LIST}></h2>

    <{$js}>
    <div class="row" style="margin:10px;">
      <div class="col-sm-5">
        <select name="nps_sn" id="nps_sn" class="form-control col-sm-6" onChange="window.location.href='newspaper.php?nps_sn='+this.value ">
          <option value=""><{$smarty.const._MA_TADNEWS_NP_OPTION}></option>
          <{$option}>
        </select>
      </div>
      <div class="col-sm-7">
        <{$create_btn}>
        <{$del_btn}>
        <{$edit_btn}>
      </div>
    </div>

    <{if $nps_sn}>
      <table class="table table-striped table-hover">
        <tr>
          <th><{$smarty.const._MA_TADNEWS_NP_TITLE}></th>
          <th><{$smarty.const._MA_TADNEWS_NP_DATE}></th>
          <th><{$smarty.const._MA_TADNEWS_FUNCTION}></th>
        </tr>

        <{foreach item=np from=$newspaper}>
          <tr>
            <td>
              <a href="../newspaper.php?op=preview&npsn=<{$np.allnpsn}>" target="_blank"><{$np.title}><{$np.number}></a>
            </td>
            <td><{$np.np_date}></td>
            <td>
              <a href="newspaper.php?op=edit_newspaper&npsn=<{$np.allnpsn}>" class="btn btn-sm btn-warning"><{$smarty.const._TADNEWS_EDIT}></a>
              <a href="javascript:delete_tad_newspaper(<{$np.allnpsn}>);" class="btn btn-sm btn-danger"><{$smarty.const._TADNEWS_DEL}></a>
              <a href="newspaper.php?op=sendmail_log&npsn=<{$np.allnpsn}>" class="btn btn-sm btn-info"><{$smarty.const._MA_TADNEWS_SEND_LOG}></a>
              <a href="newspaper.php?op=sendmail&npsn=<{$np.allnpsn}>" class="btn btn-sm btn-primary"><{$smarty.const._MA_TADNEWS_SEND_NOW}></a>
            </td>
          </tr>
        <{/foreach}>
      </table>
    <{/if}>
  <{/if}>

</div>