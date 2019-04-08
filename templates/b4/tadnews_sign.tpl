<p>
  <{$toolbar}>
</p>


<{if $op=="list_sign"}>
  <h1>
    <a href="<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$nsn}>"><{$news_title}></a>
  </h1>

  <div class="row">
    <{foreach item=sign from=$sign}>
      <div class="col-sm-2">
        <div class="card card-body bg-light m-1">
          <div><a href="index.php?uid=<{$sign.uid}>&op=list_user_sign"><{$sign.uid_name}></a></div>
          <div><{$sign.sign_time}></div>
        </div>
      </div>
    <{/foreach}>
  </div>

<{elseif $op=="list_user_sign"}>
  <h1>
    <a href="<{$xoops_url}>/userinfo.php?uid=<{$uid}>"><{$uid_name}></a>
  </h1>

  <div class="row">
    <{foreach item=sign from=$sign}>
      <div class="col-sm-3">
        <div class="card card-body bg-light m-1">
          <div>[<{$sign.nsn}>] <a href='<{$xoops_url}>/modules/tadnews/index.php?nsn=<{$sign.nsn}>'><{$sign.news_title}></a></div>
          <div><{$sign.sign_time}></div>
        </div>
      </div>
    <{/foreach}>
  </div>
<{/if}>