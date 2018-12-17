<{if $block.option==""}>
  <{$smarty.const._MB_TADNEWS_NO_NEWSPAPER}>
<{else}>
  <div class="row">
    <form action="<{$xoops_url}>/modules/tadnews/email.php" method="post">
      <{$block.counter}>

      <div class="row">
        <label class="col-sm-4" for="newspaper"><{$smarty.const._MB_TADNEWS_TITLE}></label>
        <div class="col-sm-8">
          <select name="nps_sn" class="form-control" id="newspaper" placeholder="<{$smarty.const._MB_TADNEWS_TITLE}>">
            <{foreach item=opt from=$block.option}>
            <option value="<{$opt.value}>"><{$opt.text}></option>
            <{/foreach}>
          </select>
        </div>
      </div>


      <div class="row">
        <label class="col-sm-4" for="newspaper_email"><{$smarty.const._MB_TADNEWS_EMAIL}></label>
        <div class="col-sm-8">
          <input type="text" name="newspaper_email" id="newspaper_email" placeholder="<{$smarty.const._MB_TADNEWS_EMAIL}>" value="<{$block.email}>" class="form-control">
        </div>
      </div>


      <div class="row">
        <div class="col-sm-8">
          <label class="radio-inline"><input type="radio" name="mode" value="add" checked><{$smarty.const._MB_TADNEWS_ORDER}></label>
          <label class="radio-inline"><input type="radio" name="mode" value="del"><{$smarty.const._MB_TADNEWS_CANCEL}></label>
        </div>
        <div class="col-sm-4">
            <{$XOOPS_TOKEN}>
          <button type="submit" class="btn btn-info"><{$smarty.const._MB_TADNEWS_SUBMIT}></button>
        </div>
      </div>
    </form>
  </div>
<{/if}>