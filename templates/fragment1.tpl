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
                <input type="text" name="always_top_date"  class="form-control" id="always_top_date"  value="<{$always_top_date}>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',startDate:'%y-%M-{%d+7} %H:%m',maxDate:'%y-%M-{%d+<{$top_max_day}>} %H:%m'})">
            </span>
        <{/if}>
    </div>
</div>