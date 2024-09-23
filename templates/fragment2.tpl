<div class="row">
    <div class="col-md-3">
        <label><{$smarty.const._MD_TADNEWS_CAN_READ_NEWS_GROUP}><{$smarty.const._MD_TADNEWS_FOR}></label>
        <{$enable_group|default:''}>
    </div>
    <div class="col-md-3">
        <label><{$smarty.const._MD_TADNEWS_NEWS_HAVE_READ}><{$smarty.const._MD_TADNEWS_FOR}></label>
        <{$have_read_group|default:''}>
    </div>
    <div class="col-md-3">
        <label>
        <{$smarty.const._MD_TADNEWS_NEWS_ENABLE}><{$smarty.const._MD_TADNEWS_FOR}>
        </label>
        <label class="radio">
            <input type="radio" name="enable" value="1" id="enable1" <{$enable_checked1|default:''}>><{$smarty.const._MD_TADNEWS_NEWS_ENABLE_OK}>
        </label>
            <label class="radio">
            <input type="radio" name="enable" value="0" id="enable0" <{$enable_checked0|default:''}>><{$smarty.const._TADNEWS_NEWS_UNABLE}>
        </label>
    </div>

    <div class="col-md-3">
        <div class="row">
            <label>
                <{$smarty.const._MD_TADNEWS_NEWS_PASSWD}><{$smarty.const._MD_TADNEWS_FOR}>
            </label>
            <input type="text" name="passwd" id="passwd"  class="form-control" value="<{$passwd|default:''}>">
        </div>
    </div>
</div>