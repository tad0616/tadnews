<{if $block.option==""}>
    <{$smarty.const._MB_TADNEWS_NO_NEWSPAPER}>
<{else}>
<{$block.counter}>
    <form action="<{$xoops_url}>/modules/tadnews/email.php" method="post">

        <div class="form-group row mb-3">
            <label class="col-md-4 control-label col-form-label text-md-right text-md-end" for="newspaper"><{$smarty.const._MB_TADNEWS_TITLE}></label>
            <div class="col-md-8">
                <select name="nps_sn" class="form-control" id="newspaper" placeholder="<{$smarty.const._MB_TADNEWS_TITLE}>">
                    <{foreach item=opt from=$block.option}>
                    <option value="<{$opt.value}>"><{$opt.text}></option>
                    <{/foreach}>
                </select>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-4 control-label col-form-label text-md-right text-md-end" for="newspaper_email"><{$smarty.const._MB_TADNEWS_EMAIL}></label>
            <div class="col-md-8">
                <input type="text" name="newspaper_email" id="newspaper_email" placeholder="<{$smarty.const._MB_TADNEWS_EMAIL}>" value="<{$block.email}>" class="form-control">
            </div>
        </div>

        <div class="form-group row mb-3">
            <div class="col-md-8">
                <div class="form-check-inline radio-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="mode" value="add" checked>
                        <{$smarty.const._MB_TADNEWS_ORDER}>
                    </label>
                </div>
                <div class="form-check-inline radio-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="mode" value="del">
                        <{$smarty.const._MB_TADNEWS_CANCEL}>
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <{$XOOPS_TOKEN}>
            <button type="submit" class="btn btn-info"><{$smarty.const._MB_TADNEWS_SUBMIT}></button>
            </div>
        </div>
    </form>
<{/if}>