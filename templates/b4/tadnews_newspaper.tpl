<p>
<{$toolbar}>
</p>

<h1 class="sr-only" style="display: none;"><{$smarty.const._MD_TADNEWS_NEWSPAPER}></h1>

<{if $page}>
  <table class="table table-striped table-bordered table-responsive">
    <tbody>
      <{foreach item=page from=$page}>
        <tr>
          <td class="col-sm-3"><{$page.np_date}></td>
          <td><a href="<{$xoops_url}>/modules/tadnews/newspaper.php?op=preview&npsn=<{$page.allnpsn}>" target="_blank">
          <{$page.title}></a>
          </td>
        </tr>
      <{/foreach}>
    </tbody>
  </table>
  <{$bar}>
<{/if}>