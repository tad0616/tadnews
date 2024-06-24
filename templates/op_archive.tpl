<h1 class="my"><{$smarty.const._MD_TADNEWS_ARCHIVE}></h1>
<div class="row" style="margin-bottom:20px;">
    <div class="col-sm-3">
        <div class="input-group">
            <div class="input-group-prepend input-group-addon">
                <span class="input-group-text"><{$smarty.const._MD_TADNEWS_ARCHIVE}></span>
            </div>
            <label class="sr-only visually-hidden" for="select">Preference</label>
            <select onChange="location.href='archive.php?date='+this.value" class="form-control" id="select">
                <option value=""></option>
                <{foreach item=opt from=$opt}>
                    <option value="<{$opt.value}>" <{$opt.selected}>><{$opt.text}> (<{$opt.count}>)</option>
                <{/foreach}>
            </select>
        </div>
    </div>
</div>


<{if $page}>
    <div class="panel panel-default card mb-5">
        <table class="table table-striped table-bordered">
            <tr><th><{$date_title}></th></tr>
            <{foreach from=$page item=news}>
                <tr>
                    <td>
                        <{$news.post_date}>

                        <{if $news.need_sign}>
                            <img src="<{$news.need_sign}>" alt="<{$news.news_title}>" style="margin:3px;">
                        <{/if}>
                        <div class="pull-right float-right float-end"><{$news.files}></div>
                        <a href="index.php?nsn=<{$news.nsn}>"><{$news.news_title}></a>
                        (<{$news.uid_name}>)
                    </td>
                </tr>
            <{/foreach}>
        </table>
    </div>
<{/if}>