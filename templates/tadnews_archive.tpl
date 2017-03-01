<link rel="stylesheet" type="text/css" media="screen" href="<{$xoops_url}>/modules/tadtools/css/iconize.css" />
<{$jquery}>


<p>
  <{$toolbar}>
</p>


<div class="row">
  <div class="col-sm-12">
    <div class="input-group">
      <span class="input-group-addon"><{$smarty.const._MD_TADNEWS_ARCHIVE}></span>
      <select onChange="location.href='archive.php?date='+this.value" class="form-control">
        <{foreach item=opt from=$opt}>
        <option value="<{$opt.value}>" <{$opt.selected}>><{$opt.text}> (<{$opt.count}>)</option>
        <{/foreach}>
      </select>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped table-bordered">
      <tr><th><{$date_title}></th></tr>
      <{foreach item=page from=$page}>
        <tr><td>
        <{$page.post_date}>

        <{if $page.need_sign}>
          <img src="<{$page.need_sign}>" align="absmiddle" alt="<{$page.news_title}>" style="margin:3px;">
        <{/if}>
        <div class="pull-right"><{$page.files}></div>
        <a href="index.php?nsn=<{$page.nsn}>"><{$page.news_title}></a>
        (<{$page.uid_name}>)
        </td></tr>
      <{/foreach}>
    </table>
  </div>
</div>
