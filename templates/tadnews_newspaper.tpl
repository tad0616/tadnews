<p>
<{$toolbar}>
</p>


<{if $page}>
  <div class="row">
    <table class="table table-striped table-bordered">
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
  </div>
  <{$bar}>
<{/if}>